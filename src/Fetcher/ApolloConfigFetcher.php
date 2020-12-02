<?php

namespace W7\Config\Fetcher;

use Org\Multilinguals\Apollo\Client\ApolloClient;

class ApolloConfigFetcher extends ConfigFetcherAbstract {
    protected $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function fetch() {
        $apollo = new ApolloClient($this->config['server'], $this->config['app_id'], (array)$this->config['namespace'] ?? []);

        //如果需要灰度发布，指定clientIp
        /*
         * $clientIp = '10.160.2.131';
         * if (isset($clientIp) && filter_var($clientIp, FILTER_VALIDATE_IP)) {
         *    $apollo->setClientIp($clientIp);
         * }
         */

        //从apollo上拉取的配置默认保存在脚本目录，可自行设置保存目录
        $apollo->save_dir = RUNTIME_PATH . '/config';

        $error = $apollo->start(function () {

        });
	}
}