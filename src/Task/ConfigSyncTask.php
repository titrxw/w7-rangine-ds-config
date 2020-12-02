<?php

namespace W7\Config\Task;

use W7\App;
use W7\Core\Task\TaskAbstract;

class ConfigSyncTask extends TaskAbstract {
	public function run($server, $taskId, $workId, $data) {
		foreach ($data['data'] ??[] as $key => $value) {
			App::getApp()->getConfigger()->set($key, $value);
		}
	}
}