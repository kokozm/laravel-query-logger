## 安装

```
# composer require kokozm/laravel-query-logger 
```

## 配置

在 ` .env ` 文件中增加 ` LARAVEL_QUERY_LOGGER_ENABLED=true ` 即可开启。

也可发布配置文件：

```
# php artisan vendor:publish --provider=Kokozm\LaravelQueryLogger\ServiceProvider --tag=config
```

生成配置文件 `config/laravel-query-logger.php` ，内容如下：

```php

return [
    'enabled' => env('LARAVEL_QUERY_LOGGER_ENABLED', false),
];

```

## License

[MIT license](https://opensource.org/licenses/MIT)
