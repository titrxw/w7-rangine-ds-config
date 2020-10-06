<?php

/**
 * Rangine crontab server
 *
 * (c) We7Team 2019 <https://www.rangine.com>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com for more details
 */

namespace W7\Config\Listener;

use Swoole\Server;
use W7\Core\Listener\ListenerAbstract;
use W7\Crontab\Message\ConfigFetchMessage;

class AfterPipeMessageListener extends ListenerAbstract {
	public function run(...$params) {
		/**
		 * @var Server $server
		 */
		$server = $params[0];
		/**
		 * @var ConfigFetchMessage $message
		 */
		$message = $params[2];
		$data = $params[3];

		if ($message->messageType == ConfigFetchMessage::CONFIG_FETCH_MESSAGE) {

		}
	}
}
