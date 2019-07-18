## Server
```

$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$tcp = $http->addlistener('0.0.0.0',9600,SWOOLE_TCP);

$console = new \EasySwoole\Console\Console();
$console->protocolSet($tcp)->attachToServer($http);

$http->start();


```

## Client 
```
telnet 127.0.0.1 9500
```

https://github.com/easy-swoole/console/tree/ff2903439efcf9c6ba535b6ba89cb3308313bb3d