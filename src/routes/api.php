<?php

/*
 * This file is part of ibrand/laravel-satis.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::post('/git/webhook', 'SatisController@satis');

//Route::get('/git/webhook', 'SatisController@satis');
