<?php

namespace W7\Config\Event;

class ConfigSyncedEvent {
	public $data;
	public $workId;
	public $taskId;

	public function __construct(array $data, $workId, $taskId) {
		$this->data = $data;
		$this->workId = $workId;
		$this->taskId = $taskId;
	}
}