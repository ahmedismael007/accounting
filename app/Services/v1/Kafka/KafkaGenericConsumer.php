<?php

namespace App\Services\v1\Kafka;

use App\Events\TenantCreated;
use App\Events\TenantDeleted;
use App\Events\TenantUpdated;
use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Facades\Kafka;

class KafkaGenericConsumer
{
    public function run(): void
    {
        $consumer = Kafka::consumer(groupId:'accounting_service')->subscribe([
            'user_created',
            'user_updated',
            'user_deleted',
            'tenant_created',
            'tenant_updated',
            'tenant_deleted',
        ]);

        $consumer->withHandler(function (ConsumerMessage $message) {
            $topic   = $message->getTopicName();
            $headers = $message->getHeaders();
            $payload = (array) $message->getBody();

            $tenantId = $headers['X-Tenant'] ?? null;

            try {
                if ($tenantId) {
                    tenancy()->initialize($tenantId);
                    Log::info("Initialized tenant: {$tenantId}");
                }

                switch ($topic) {
                    case 'user_created':
                        Event::dispatch(new UserCreated($payload));
                        break;

                    case 'user_updated':
                        Event::dispatch(new UserUpdated($payload));
                        break;

                    case 'user_deleted':
                        Event::dispatch(new UserDeleted($payload));
                        break;

                    case 'tenant_created':

                        Event::dispatch(new TenantCreated($payload));
                        break;

                    case 'tenant_updated':
                        Event::dispatch(new TenantUpdated($payload));
                        break;

                    case 'tenant_deleted':
                        Event::dispatch(new TenantDeleted($payload));
                        break;

                    default:
                        Log::warning("Unhandled topic: {$topic}", $payload);
                        break;
                }
            } finally {
                if ($tenantId) {
                    tenancy()->end();
                    Log::info("Ended tenant: {$tenantId}");
                }
            }
        })->build()->consume();
    }
}
