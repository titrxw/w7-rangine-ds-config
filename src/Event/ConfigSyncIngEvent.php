<?php

namespace W7\Config\Event;

use W7\Config\Message\ConfigFetchMessage;

class ConfigSyncIngEvent {
	/**
	 * @var ConfigFetchMessage
	 */
	public $configFetchMessage;
	public $workerId;

	public function __construct(ConfigFetchMessage $message, $workerId) {
		$this->configFetchMessage = $message;
		$this->workerId = $workerId;
	}
}