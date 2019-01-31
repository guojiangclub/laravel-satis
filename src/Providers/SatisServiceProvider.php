<?php

/*
 * This file is part of ibrand/laravel-satis.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Satis\Providers;

use iBrand\Satis\Console\InstallCommand;
use Illuminate\Support\ServiceProvider;

class SatisServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(public_path('satis'), 'satis');

        $this->publishes([
            __DIR__.'/../../config/satis.php' => config_path('ibrand/satis.php'),
        ]);

    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->commands(InstallCommand::class);
    }
}
