<?php

namespace W7\Config\Fetcher;

use Swoole\Coroutine\Socket;
use Throwable;
use W7\App;
use W7\Config\Event\ConfigSyncExceptionEvent;
use W7\Config\Event\ConfigSyncIngEvent;
use W7\Config\Task\ConfigSyncTask;
use W7\Core\Helper\Traiter\AppCommonTrait;
use W7\Core\Process\ProcessServerAbstract;
use W7\Config\Message\ConfigFetchMessage;

abstract class ConfigFetcherAbstract {
	use AppCommonTrait;

	abstract public function fetch();

	protected function syncConfig(array $data) {
		$pipeMessage = new ConfigFetchMessage();
		$pipeMessage->params['data'] = $data;
		$pipeMessage->task = ConfigSyncTask::class;

		if (!App::$server instanceof ProcessServerAbstract) {
			$workerCount = App::$server->setting['worker_num'] + App::$server->setting['task_worker_num'] - 1;
			for ($workerId = 0; $workerId <= $workerCount; ++$workerId) {
				try {
					App::$server->getServer()->sendMessage($pipeMessage->pack(), $workerId);

					$this->getEventDispatcher()->dispatch(new ConfigSyncIngEvent($pipeMessage, $workerId));
				} catch (Throwable $e) {
					$this->getEventDispatcher()->dispatch(new ConfigSyncExceptionEvent($e, $pipeMessage, $workerId));
				}
			}
		}

        if (!empty(App::$server->processPool)) {
            for ($processId = 0; $processId < App::$server->processPool->getProcessFactory()->count(); ++$processId) {
                $process = App::$server->processPool->getProcess($processId);
                if ($process) {
                    try {
                        /**
                         * @var Socket $socket
                         */
                        $socket = $process->getProcess()->exportSocket();
                        $result = $socket->send($pipeMessage->pack(), 10);
                        if ($result === false) {
                            throw new \Exception('Configuration synchronization failed. Please restart the server.');
                        }

                        $this->getEventDispatcher()->dispatch(new ConfigSyncIngEvent($pipeMessage, $processId));
                    } catch (Throwable $e) {
                        $this->getEventDispatcher()->dispatch(new ConfigSyncExceptionEvent($e, $pipeMessage, $processId));
                    }
                }
            }
        }

	}
}