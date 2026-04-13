<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Client\MerchantClient;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Tests\Support\FakeHttpClient;

final class SessionContextTest extends TestCase
{
    public function testMerchantSessionContextOverridesCallerIdentifiers(): void
    {
        $httpClient = new FakeHttpClient(array_fill(0, 6, [
            'ret' => 1,
            'msg' => 'success',
            'data' => [],
        ]));
        $client = new MerchantClient(
            new MerchantConfig('https://example.com', 'MCH202604110001', 'default', 'merchant-secret'),
            $httpClient
        );
        $session = $client->session('20260413SCTX001');

        $session->close(['session_id' => 'WRONG_CLOSE', 'reason' => 'manual']);
        $session->sseSign(['session_id' => 'WRONG_SSE', 'expire_seconds' => 120]);
        $session->switchModel(['session_id' => 'WRONG_SWITCH', 'model_id' => 9]);
        $session->messages()->getList(['s_session_id' => 'WRONG_LIST', 'page' => 2]);
        $session->messages()->send(['session_id' => 'WRONG_SEND', 'request_id' => 'req_001']);
        $session->jobs()->getList(['s_session_id' => 'WRONG_JOB_LIST', 'page' => 3]);

        $requests = $httpClient->requests();
        self::assertSame('20260413SCTX001', $this->decodeJsonBody($requests[0])['session_id']);
        self::assertSame('20260413SCTX001', $this->decodeJsonBody($requests[1])['session_id']);
        self::assertSame('20260413SCTX001', $this->decodeJsonBody($requests[2])['session_id']);
        self::assertSame('20260413SCTX001', $requests[3]['query']['s_session_id']);
        self::assertSame('20260413SCTX001', $this->decodeJsonBody($requests[4])['session_id']);
        self::assertSame('20260413SCTX001', $requests[5]['query']['s_session_id']);
    }

    public function testSystemSessionContextOverridesCallerIdentifiers(): void
    {
        $httpClient = new FakeHttpClient(array_fill(0, 6, [
            'ret' => 1,
            'msg' => 'success',
            'data' => [],
        ]));
        $client = new SystemClient(
            new SystemConfig('https://example.com', 'system-api-key'),
            $httpClient
        );
        $session = $client->session('20260413SYSCTX001');

        $session->close(['session_id' => 'WRONG_CLOSE', 'reason' => 'manual']);
        $session->sseSign(['session_id' => 'WRONG_SSE', 'expire_seconds' => 120]);
        $session->switchModel(['session_id' => 'WRONG_SWITCH', 'model_id' => 9]);
        $session->messages()->getList(['s_session_id' => 'WRONG_LIST', 'page' => 2]);
        $session->messages()->send(['session_id' => 'WRONG_SEND', 'request_id' => 'req_001']);
        $session->jobs()->getList(['s_session_id' => 'WRONG_JOB_LIST', 'page' => 3]);

        $requests = $httpClient->requests();
        self::assertSame('20260413SYSCTX001', $this->decodeJsonBody($requests[0])['session_id']);
        self::assertSame('20260413SYSCTX001', $this->decodeJsonBody($requests[1])['session_id']);
        self::assertSame('20260413SYSCTX001', $this->decodeJsonBody($requests[2])['session_id']);
        self::assertSame('20260413SYSCTX001', $requests[3]['query']['s_session_id']);
        self::assertSame('20260413SYSCTX001', $this->decodeJsonBody($requests[4])['session_id']);
        self::assertSame('20260413SYSCTX001', $requests[5]['query']['s_session_id']);
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    private function decodeJsonBody(array $request): array
    {
        self::assertIsString($request['body']);
        $decoded = json_decode($request['body'], true);
        self::assertIsArray($decoded);

        return $decoded;
    }
}
