<?php

namespace W7\Config\Event;

class ConfigFetchedEvent {
	public $data;
	public $appId;

	public function __construct(array $data, $appId) {
		$this->data = $data;
		$this->appId = $appId;
	}
}