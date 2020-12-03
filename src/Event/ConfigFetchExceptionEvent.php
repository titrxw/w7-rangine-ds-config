<?php

namespace W7\Config\Event;

class ConfigFetchExceptionEvent {
	public $errorMessage;
	public $appId;

	public function __construct($errorMessage, $appId) {
		$this->errorMessage = $errorMessage;
		$this->appId = $appId;
	}
}