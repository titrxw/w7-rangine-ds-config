<?php

namespace W7\Config\Fetcher\Apollo;

use W7\Config\Event\ConfigFetchExceptionEvent;
use W7\Config\Event\ConfigFetchedEvent;
use W7\Config\Fetcher\ConfigFetcherAbstract;

class ApolloConfigFetcher extends ConfigFetcherAbstract {
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

		$systemConfigPath = BASE_PATH . '/vendor/composer/rangine/autoload/config';;
		$error = $apollo->start(function () use ($apollo, $namespaces, $systemConfigPath) {
			$config = [];
			foreach ($namespaces as $namespace) {
				$path = $apollo->getConfigFile($namespace);
				//在opcache开启时文件变化，include无效ß
				if (file_exists($path)) {
					$config[$namespace] = include $path;
					$content = '<?php return '.var_export($config[$namespace]['configurations'] ?? [], true).';';
					file_put_contents($systemConfigPath . '/' . $namespace . '.php', $content);
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