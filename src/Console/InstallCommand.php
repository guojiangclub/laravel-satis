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
use Illuminate\Support\ProcessUtils;
use Symfony\Component\Process\PhpExecutableFinder;


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

        $arr = explode('/', last(explode(':', $git)));

        $tag_arr[] = $arr[0];

        $tag_arr[] = explode('.', last($arr))[0];

        $name = $tag_arr[0] . '/' . $tag_arr[1];

        $releases = last(explode('v', explode('.', $tag)[0]));

        if (!file_exists($destinationPath . '/satis.json')) {

            $contents = $this->setSatisJson($name,$git,$releases);

            file_put_contents($destinationPath . '/satis.json', $contents);
        }

        $laravel_satis = 'vendor/ibrand/laravel-satis';

        $php = ProcessUtils::escapeArgument((new PhpExecutableFinder())->find(false));

        $satis = app()->basePath() . '/vendor/bin/satis';

        if ($php) {

            $php = trim($php, "'");
        }

        $shell = "$php $satis build $laravel_satis/$git/$tag/satis.json satis $name 2>&1";

        exec($shell, $result, $status);

        if (config('ibrand.satis.log')) {
            if ($status) {
                \Log::info("shell命令{$shell}执行失败");
                \Log::info($result);
            } else {
                \Log::info("shell命令{$shell}执行成功");
                \Log::info($result);
            }
        }


    }

    protected function setSatisJson($name,$git,$releases)
    {

        $satis = [
            'name' => config('ibrand.satis.name'),
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
