## 安装

```
# composer require kokozm/laravel-query-logger 
```

## 配置

```
# php artisan vendor:publish --provider=Kokozm\LaravelQueryLogger\ServiceProvider --tag=config
```

生成配置文件 `config/laravel-query-logger.php` ，内容如下：

```php

return [
    'enabled' => env('LARAVEL_QUERY_LOGGER_ENABLED', false),
];

```

当 ` enabled ` 为true时才记录

## License

[MIT license](https://opensource.org/licenses/MIT)
