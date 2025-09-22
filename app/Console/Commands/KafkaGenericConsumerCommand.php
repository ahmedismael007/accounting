<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\v1\Kafka\KafkaGenericConsumer;

class KafkaGenericConsumerCommand extends Command
{
    protected $signature = 'kafka:consume:generic';
    protected $description = 'Run a generic Kafka consumer for multiple topics';

    public function handle(KafkaGenericConsumer $consumer)
    {
        $this->info('Starting generic Kafka consumer...');
        $consumer->run();
    }
}
