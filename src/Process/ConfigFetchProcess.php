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

namespace W7\Config\Process;

use Swoole\Process;

use W7\Config\Fetcher\ConfigFetcherAbstract;
use W7\Core\Process\ProcessAbstract;

class ConfigFetchProcess extends ProcessAbstract {
	public function check() {
		return true;
	}

	protected function run(Process $process) {
		/**
		 * @var ConfigFetcherAbstract $dsConfigFetcher
		 */
		$dsConfigFetcher = $this->getContainer()->get('ds-config-fetch');
		$dsConfigFetcher->fetch();
	}
}
