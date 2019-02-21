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

        if (config('ibrand.satis.log')) {

            \Log::info($all);
        }


        if(isset($all['payload'])){

            $data=json_decode($all['payload']);

            $all['repository']['git_ssh_url']=$data->repository->ssh_url;

            $all['ref']='refs/tags/'.$data->ref;

        }
        
        if (isset($all['repository']['git_ssh_url']) AND isset($all['ref'])) {

            $git = $all['repository']['git_ssh_url'];

            $tag = last(explode('/', $all['ref']));

            Artisan::call('ibrand-statis:webhook', ['git' => $git, 'tag' => $tag]);

            return 'success';

        }


        return 'webhook info error';
    }
}
