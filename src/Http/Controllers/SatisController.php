<?php

/*
 * This file is part of ibrand/laravel-satis.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Satis\Http\Controllers;

use Artisan;
use Illuminate\Routing\Controller;

class SatisController extends Controller
{
    public function index()
    {
        if (file_exists(public_path('satis') . '/index.html')) {
            $content = file_get_contents(public_path('satis') . '/index.html');

            echo $content;
        }
    }

    public function satis()
    {

        $all = request()->all();

        if (isset($all['object_kind']) and isset($all['ref']) and 'tag_push' == $all['object_kind']) {
            $git = $all['repository']['url'];

            $tag = last(explode('/', $all['ref']));

            Artisan::call('ibrand-statis:webhook', ['git' => $git, 'tag' => $tag]);

            return 'success';
        }

        \Log::info($all);

        return 'webhook info error';
    }
}
