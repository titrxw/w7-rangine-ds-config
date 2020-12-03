<?php

namespace W7\Config;

use W7\Config\Fetcher\ApolloConfigFetcher;
use W7\Config\Listener\AfterPipeMessageListener;
use W7\Config\Server\Server;
use W7\Core\Provider\ProviderAbstract;
use W7\Core\Server\ServerEvent;

class ServiceProvider extends ProviderAbstract {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerServer('config', Server::class);
		
		$this->getEventDispatcher()->listen(ServerEvent::ON_USER_AFTER_PIPE_MESSAGE, AfterPipeMessageListener::class);

		$this->container->set('ds-config-fetch', function () {
			return new ApolloConfigFetcher($this->config->get('apollo', []));
		});
	}
}
