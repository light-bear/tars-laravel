<?php
return [
    'proto' => [
        'appName' => 'TarsApp', // 对应部署服务的应用名称
        'serverName' => 'DemoServer', // 对应部署服务的服务名称
        'objName' => 'Http', // 对应部署服务的obj
    ],
    'services' => [
        'HttpObj' => [
            'protocolName' => 'http', //http, json, tars or other
            'serverType' => 'http', //http(no_tars default), websocket, tcp(tars default), udp
            'namespaceName' => 'PHPHttp\\', //websocket,http require
            'monitorStoreConf' => [
                //'className' => Tars\monitor\cache\RedisStoreCache::class,
                //'config' => [
                // 'host' => '127.0.0.1',
                // 'port' => 6379,
                // 'password' => ':'
                //],
                'className' => Tars\monitor\cache\SwooleTableStoreCache::class,
                'config' => [
                    'size' => 40960
                ]
            ],
            'registryStoreConf' => [
                'className' => Tars\registry\RouteTable::class,
                'config' => [
                    'size' => 200
                ]
            ],
        ],
        'TarsObj' => [
            'protocolName' => 'tars',
            //http, json, tars or other
            'serverType' => 'tcp',
            //http(no_tars default), websocket, tcp(tars default), udp
            'home-api' => App\TarsServants\TarsApp\DemoServer\TarsObj\TarsDemoServant::class,
            //根据实际情况替换，遵循PSR-4即可，与tars.proto.php配置一致
            'home-class' => App\TarsServices\TarsDemoService::class,
            //根据实际情况替换，遵循PSR-4即可
        ],
    ],
];