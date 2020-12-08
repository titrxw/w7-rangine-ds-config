<?php

namespace W7\Config\Fetcher\Apollo;

class ApolloClient extends \Org\Multilinguals\Apollo\Client\ApolloClient {
    //获取单个namespace的配置文件路径
    public function getConfigFile($namespaceName) {
        return $this->save_dir.DIRECTORY_SEPARATOR.$namespaceName.'.php';
    }
}