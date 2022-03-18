<?php

use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Redis
| - pipeline
| - pub / sub
| - Wildcard Subscriptions
|--------------------------------------------------------------------------
*/

Route::prefix('redis')->group(function () {
    Route::get('/pipeline', function () {

        Redis::pipeline(function ($pipe) {
            for ($i = 0; $i < 10; $i++) {
                $pipe->set("cvi-key:$i", $i);
            }
        });

    });

    Route::get('/publish', function () {

        Redis::publish('test-channel', json_encode([
            'name' => 'Cristian Visan',
            'age' => 40,
            'time' => now()
        ]));

        echo 'OK';
    });

    Route::get('/publish-2', function () {

        Redis::publish('users.profile', json_encode([
            'name' => 'Cristian Visan',
            'age' => 40,
            'time' => now()
        ]));

        echo 'OK';
    });
});
