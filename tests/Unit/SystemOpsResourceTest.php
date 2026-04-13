<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Tests\Support\FakeHttpClient;

final class SystemOpsResourceTest extends TestCase
{
    public function testSystemOpsResourceMatchesServerRoutes(): void
    {
        $httpClient = new FakeHttpClient(array_fill(0, 5, [
            'ret' => 1,
            'msg' => 'success',
            'data' => [],
        ]));
        $client = new SystemClient(
            new SystemConfig('https://example.com', 'system-api-key'),
            $httpClient
        );

        $client->ops()->queueStatus();
        $client->ops()->workers();
        $client->ops()->providerHealth(['limit' => 8]);
        $client->ops()->failedJobs(['limit' => 5, 's_source_system_code' => 'default']);
        $client->ops()->trace(['s_request_id' => 'req_001']);

        $requests = $httpClient->requests();
        self::assertSame([
            '/admin/system/v1/ops/queue-status',
            '/admin/system/v1/ops/workers',
            '/admin/system/v1/ops/provider-health',
            '/admin/system/v1/ops/failed-jobs',
            '/admin/system/v1/ops/trace',
        ], array_column($requests, 'path'));
        self::assertSame(['limit' => 8], $requests[2]['query']);
        self::assertSame([
            'limit' => 5,
            's_source_system_code' => 'default',
        ], $requests[3]['query']);
        self::assertSame(['s_request_id' => 'req_001'], $requests[4]['query']);
        self::assertSame('system-api-key', $requests[0]['headers']['X-System-Key']);
    }
}
