<?php

/**
 * This file is part of Rangine
 *
 * (c) We7Team 2019 <https://www.rangine.com/>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com/ for more details
 */

namespace W7\Config\Server;

use W7\Config\Process\ConfigFetchProcess;
use W7\Core\Process\ProcessServerAbstract;

class Server extends ProcessServerAbstract {
	public static $onlyFollowMasterServer = true;

	public function __construct() {
		$this->getConfig()->set('server.' . $this->getType(), [
			'worker_num' => 1
		]);

		parent::__construct();
	}

	public function getType() {
		return 'config';
	}

	protected function register() {
		$this->pool->registerProcess('fetcher', ConfigFetchProcess::class, 1);
	}

	public function start() {
		throw new \Exception('cannot start alone');
	}
}
