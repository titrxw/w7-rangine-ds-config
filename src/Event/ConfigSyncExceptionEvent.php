<?php

namespace W7\Config\Event;

use Throwable;
use W7\Config\Message\ConfigFetchMessage;

class ConfigSyncExceptionEvent {
	/**
	 * @var Throwable
	 */
	public $exception;
	/**
	 * @var ConfigFetchMessage
	 */
	public $configFetchMessage;
	public $workerId;

	public function __construct(Throwable $throwable, ConfigFetchMessage $message, $workerId) {
		$this->exception = $throwable;
		$this->configFetchMessage = $message;
		$this->workerId = $workerId;
	}
}