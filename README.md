## Server
```
$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$config = new \EasySwoole\Console\Config();
\EasySwoole\Console\Console::getInstance()->attachServer($http,$config);

$http->start();

```

## Client 
```
telnet 127.0.0.1 9500
```

https://github.com/easy-swoole/console/tree/ff2903439efcf9c6ba535b6ba89cb3308313bb3d