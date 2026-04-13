<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Client\MerchantClient;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Factory;

final class FactoryTest extends TestCase
{
    public function testFactoryCreatesMerchantClientFromConfigObject(): void
    {
        $client = Factory::merchant(
            new MerchantConfig('https://example.com', 'MCH202604110001', 'default', 'merchant-secret')
        );

        self::assertInstanceOf(MerchantClient::class, $client);
    }

    public function testFactoryCreatesMerchantClientFromArray(): void
    {
        $client = Factory::merchant([
            'base_uri' => 'https://example.com',
            'mch_no' => 'MCH202604110001',
            'source_system' => 'default',
            'access_secret' => 'merchant-secret',
        ]);

        self::assertInstanceOf(MerchantClient::class, $client);
    }

    public function testFactoryCreatesSystemClientFromConfigObject(): void
    {
        $client = Factory::system(new SystemConfig('https://example.com', 'system-api-key'));

        self::assertInstanceOf(SystemClient::class, $client);
    }

    public function testFactoryCreatesSystemClientFromArray(): void
    {
        $client = Factory::system([
            'base_uri' => 'https://example.com',
            'system_key' => 'system-api-key',
        ]);

        self::assertInstanceOf(SystemClient::class, $client);
    }
}
