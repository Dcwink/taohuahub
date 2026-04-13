<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Client\MerchantClient;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Tests\Support\FakeHttpClient;

final class SessionResourceTest extends TestCase
{
    public function testMerchantSessionResourceMatchesServerRoutes(): void
    {
        $httpClient = new FakeHttpClient(array_fill(0, 2, [
            'ret' => 1,
            'msg' => 'success',
            'data' => [],
        ]));
        $client = new MerchantClient(
            new MerchantConfig('https://example.com', 'MCH202604110001', 'default', 'merchant-secret'),
            $httpClient
        );

        $client->sessions()->getList(['page' => 1, 's_actor_id' => '10001']);
        $client->sessions()->create([
            'chat_mode' => 'model',
            'actor_type' => 'user',
            'actor_id' => '10001',
            'isnew' => true,
            'model_id' => 1,
        ]);

        $requests = $httpClient->requests();
        self::assertSame('/admin/merchant/v1/sessions', $requests[0]['path']);
        self::assertSame(['page' => 1, 's_actor_id' => '10001'], $requests[0]['query']);
        self::assertSame('/admin/merchant/v1/sessions/create', $requests[1]['path']);
    }

    public function testSystemSessionResourceMatchesServerRoutes(): void
    {
        $httpClient = new FakeHttpClient(array_fill(0, 2, [
            'ret' => 1,
            'msg' => 'success',
            'data' => [],
        ]));
        $client = new SystemClient(
            new SystemConfig('https://example.com', 'system-api-key'),
            $httpClient
        );

        $client->sessions()->getList(['page' => 1, 's_mchno' => 'MCH202604110001']);
        $client->sessions()->create([
            'chat_mode' => 'model',
            'actor_type' => 'user',
            'actor_id' => '10001',
            'isnew' => true,
            'model_id' => 1,
            'mchno' => 'MCH202604110001',
        ]);

        $requests = $httpClient->requests();
        self::assertSame('/admin/system/v1/ai/sessions', $requests[0]['path']);
        self::assertSame(['page' => 1, 's_mchno' => 'MCH202604110001'], $requests[0]['query']);
        self::assertSame('/admin/system/v1/ai/sessions/create', $requests[1]['path']);
    }
}
