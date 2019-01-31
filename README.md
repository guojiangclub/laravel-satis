# ibrand/laravel-satis 

搭建私有的包仓库，实现一个私有的 packagist。


####  安装

```
composer require ibrand/laravel-satis:~1.0 -vvv
```

#### 低于laravel5.5需要在 config/app.php 注册 ServiceProvider 
```
'providers' => [
    // ...
    iBrand\Satis\Providers\SatisServiceProvider::class,
    iBrand\Satis\Providers\RouteServiceProvider::class,
],

```
#### 发布资源配置

```
php artisan vendor:publish --all
```








