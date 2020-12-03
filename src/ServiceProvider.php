<?php

namespace W7\Config;

use W7\Config\Fetcher\ApolloConfigFetcher;
use W7\Config\Server\Server;
use W7\Core\Provider\ProviderAbstract;

class ServiceProvider extends ProviderAbstract {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerServer('ds-config', Server::class);

		$this->container->set('ds-config-fetch', function () {
			return new ApolloConfigFetcher($this->config->get('apollo', []));
		});
	}
}
