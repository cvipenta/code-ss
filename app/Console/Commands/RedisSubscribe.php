<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        /*/
        Redis::subscribe(['test-channel'], function ($message) {
            echo __LINE__;
            dump($message);
        });
        //*/

        /*/
        Redis::psubscribe(['users.*'], function ($message, $channel) {
            echo __LINE__ . $channel;
            dump($message);
        });
        //*/

        //*/
        Redis::psubscribe(['*'], function ($message, $channel) {
            echo __LINE__ . $channel;
            dump($message);
        });
        //*/

        return CommandAlias::SUCCESS;
    }
}
