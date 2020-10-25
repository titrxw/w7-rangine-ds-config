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

namespace W7\Config\Message;

use W7\Core\Message\TaskMessage;

/**
 * 计划任务消息包
 */
class ConfigFetchMessage extends TaskMessage {
	const CONFIG_FETCH_MESSAGE = 'config-fetch';

	public $messageType = self::CONFIG_FETCH_MESSAGE;
}
