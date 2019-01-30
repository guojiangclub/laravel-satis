<?php

/*
 * This file is part of ibrand/laravel-satis.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Satis\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'ibrand-statis:webhook {git} {tag}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ibrand statis git webhook';

    public function handle()
    {
        $git = $this->argument('git');

        $tag = $this->argument('tag');

        $destinationPath = public_path('vendor/ibrand/laravel-satis/' . $git) . '/' . $tag;

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $contents = $this->setSatisJson($git, $tag);

        if (!file_exists($destinationPath . '/satis.json')) {
            file_put_contents($destinationPath . '/satis.json', $contents);
        }

        $laravl_satis = 'vendor/ibrand/laravel-satis';

        $shell = "php  $laravl_satis/satis  build $laravl_satis/$git/$tag/satis.json  satis";

        exec($shell, $result, $status);

        if ($status) {
            \Log::info('error');

            return false;
        }

        return true;
    }

    protected function setSatisJson($git, $server)
    {
        $arr = explode('/', last(explode(':', $git)));

        $tag[] = $arr[0];

        $tag[] = explode('.', last($arr))[0];

        $name = $tag[0] . '/' . $tag[1];

        $releases = last(explode('v', explode('.', $server)[0]));

        $satis = [
            'name' => 'iBrand Private Composer',
            'homepage' => route('satis'),
            'repositories' => [[
                'type' => 'vcs',
                'url' => $git,
            ]],
            'require' => [
                $name => '~' . $releases . '.0',
            ],
            'archive' => [
                'directory' => 'dist',
                'format' => 'tar',
                'prefix-url' => route('satis'),
            ],
        ];

        return stripslashes(json_encode($satis));
    }
}
