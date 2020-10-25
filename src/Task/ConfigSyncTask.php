<?php

namespace W7\Config\Task;

use W7\Core\Facades\Config;
use W7\Core\Task\TaskAbstract;

class ConfigSyncTask extends TaskAbstract {
	public function run($server, $taskId, $workId, $data) {
		foreach ($data['data'] ??[] as $key => $value) {
			Config::set($key, $value);
		}
	}
}