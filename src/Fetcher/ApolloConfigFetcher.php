<?php

namespace W7\Config\Fetcher;

use Org\Multilinguals\Apollo\Client\ApolloClient;
use W7\Config\Event\ConfigFetchExceptionEvent;
use W7\Config\Event\ConfigFetchedEvent;

class ApolloConfigFetcher extends ConfigFetcherAbstract {
	protected $config;

	public function __construct(array $config) {
		$this->config = $config;
	}

	public function fetch() {
		$namespaces = (array)$this->config['namespace'];
		$apollo = new ApolloClient($this->config['server'], $this->config['app_id'], $namespaces);
		$apollo->setIntervalTimeout($this->config['timeout'] ?? 80);

		//如果需要灰度发布，指定clientIp
		$clientIp = $this->config['client_id'] ?? '';
		if (isset($clientIp) && filter_var($clientIp, FILTER_VALIDATE_IP)) {
			$apollo->setClientIp($clientIp);
		}

		//从apollo上拉取的配置默认保存在脚本目录，可自行设置保存目录
		$apollo->save_dir = RUNTIME_PATH . '/config';
		if (!is_dir($apollo->save_dir)) {
			mkdir($apollo->save_dir);
		}

		$error = $apollo->start(function () use ($apollo, $namespaces) {
			$config = [];
			foreach ($namespaces as $namespace) {
				$path = $apollo->getConfigFile($namespace);
				//在opcache开启时文件变化，include无效
				if (file_exists($path)) {
					$config[$namespace] = include $path;
				}
			}

			$this->getEventDispatcher()->dispatch(new ConfigFetchedEvent($config, $this->config['app_id']));

			$this->syncConfig($config);
		});

		if ($error) {
			$this->getEventDispatcher()->dispatch(new ConfigFetchExceptionEvent($error, $this->config['app_id']));
		}
	}
}