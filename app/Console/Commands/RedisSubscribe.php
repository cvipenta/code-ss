<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RedisSubscribe extends Command
{
    public const DEFAULT_SUBSCRIPTION_TYPE = 'subscribe';
    public const MULTIPLE_SUBSCRIPTION_TYPE = 'psubscribe';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        redis:subscribe
        {subscriptionType=subscribe}
        {--c|channel= : Channel name string[] or wildcard }
    ';

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
        $type = $this->argument('subscriptionType') ?? self::DEFAULT_SUBSCRIPTION_TYPE;
        $channel = $this->option('channel')
            ?? $this->choice(
                'Please provide with the channel',
                $type == self::DEFAULT_SUBSCRIPTION_TYPE ? ['test-channel'] : ['users.*', '*'],
                0,
                $maxAttempts = null,
                $allowMultipleSelections = false
            );

        $this->line('Subscribed with type <info>[' . $type . ']</info> on channel <info>[' . $channel . ']</info>');

        if ($type === self::DEFAULT_SUBSCRIPTION_TYPE) {
            Redis::subscribe([$channel], function ($message) {
                echo __LINE__;
                dump($message);
            });
        }

        if ($type === self::MULTIPLE_SUBSCRIPTION_TYPE) {

            Redis::psubscribe([$channel], function ($message, $channel) {
                echo __LINE__ . $channel;
                dump($message);
            });
        }

        return CommandAlias::SUCCESS;
    }
}
