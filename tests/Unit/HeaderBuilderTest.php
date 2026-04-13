<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Client\MerchantClient;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Internal\Auth\MerchantSigner;
use TaohuaHub\Ai2\Tests\Support\FakeHttpClient;

final class HeaderBuilderTest extends TestCase
{
    public function testMerchantSignerMatchesServerSignatureAlgorithm(): void
    {
        $signature = (new MerchantSigner())->sign(
            'MCH202604110001',
            'default',
            '1712888888',
            'abc123xyz',
            'POST',
            '/admin/merchant/v1/messages/send',
            '{"session_id":"20260411S0A1","content":"hello"}',
            'merchant-secret'
        );

        self::assertSame(
            'a83796e7f33c00e68d8ff6c5cf00e4e190e8a1d690259bfe53abc1ca8ae26463',
            $signature
        );
    }

    public function testMerchantClientBuildsRequiredAuthHeaders(): void
    {
        $httpClient = new FakeHttpClient();
        $client = new MerchantClient(
            new MerchantConfig('https://example.com', 'MCH202604110001', 'default', 'merchant-secret'),
            $httpClient
        );

        $client->messages()->send([
            'session_id' => '20260413S001',
            'request_id' => 'req_001',
            'sender_actor_type' => 'user',
            'sender_actor_id' => '10001',
        ]);

        $headers = $httpClient->lastRequest()['headers'];
        self::assertSame('MCH202604110001', $headers['X-Mch-No']);
        self::assertSame('default', $headers['X-Source-System']);
        self::assertMatchesRegularExpression('/^\d+$/', $headers['X-Timestamp']);
        self::assertMatchesRegularExpression('/^[a-f0-9]{16}$/', $headers['X-Nonce']);
        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $headers['X-Signature']);
        self::assertMatchesRegularExpression('/^req_\d{14}_[a-f0-9]{8}$/', $headers['X-Request-Id']);
    }

    public function testSystemClientBuildsRequiredAuthHeaders(): void
    {
        $httpClient = new FakeHttpClient();
        $client = new SystemClient(
            new SystemConfig('https://example.com', 'system-api-key'),
            $httpClient
        );

        $client->models()->getList();

        $headers = $httpClient->lastRequest()['headers'];
        self::assertSame('system-api-key', $headers['X-System-Key']);
        self::assertMatchesRegularExpression('/^req_\d{14}_[a-f0-9]{8}$/', $headers['X-Request-Id']);
    }
}
