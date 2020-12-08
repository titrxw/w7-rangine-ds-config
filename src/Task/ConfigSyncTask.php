<?php

namespace W7\Config\Task;

use W7\App;
use W7\Config\Event\ConfigSyncedEvent;
use W7\Core\Helper\Traiter\AppCommonTrait;
use W7\Core\Task\TaskAbstract;

class ConfigSyncTask extends TaskAbstract {
	use AppCommonTrait;

	public function run($server, $taskId, $workId, $data) {
		foreach ($data['data'] ??[] as $namespace => $value) {
			foreach ($value['configurations'] as $key => $configuration) {
				App::getApp()->getConfigger()->set($namespace . '.' . $key, $configuration);
			}
		}

		$this->getEventDispatcher()->dispatch(new ConfigSyncedEvent($data, $workId, $taskId));
	}
}