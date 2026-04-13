<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Internal\Http\Transport\GuzzleHttpClient;

final class SystemStreamMessageSendTest extends TestCase
{
    public function testSystemMessageSendSupportsStreamModeEndToEnd(): void
    {
        $history = [];
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/event-stream'],
                <<<SSE
event: ack
data: {"session_id":"20260413S001","job_id":"20260413J001","response_mode":"stream"}

event: done
data: {"session_id":"20260413S001","job_id":"20260413J001","status":"success","content":"hello"}

SSE
            ),
        ]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));
        $httpClient = new GuzzleHttpClient(new Client(['handler' => $stack]));
        $client = new SystemClient(new SystemConfig('https://example.com', 'system-api-key'), $httpClient);

        $result = $client->session('20260413S001')->messages()->send([
            'request_id' => 'req_001',
            'sender_actor_type' => 'user',
            'sender_actor_id' => '10001',
            'response_mode' => 'stream',
            'content' => 'hello',
        ]);

        self::assertTrue($result->isSuccess());
        self::assertSame('stream', $result->data()['response_mode']);
        self::assertSame('done', $result->data()['terminal_event']['event']);
        self::assertCount(1, $history);
        self::assertSame('/admin/system/v1/ai/messages/send', $history[0]['request']->getUri()->getPath());
        self::assertSame('system-api-key', $history[0]['request']->getHeaderLine('X-System-Key'));

        $payload = json_decode((string) $history[0]['request']->getBody(), true);
        self::assertSame('20260413S001', $payload['session_id']);
        self::assertSame('stream', $payload['response_mode']);
    }
}
