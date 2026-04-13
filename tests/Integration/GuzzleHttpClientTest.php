<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Internal\Http\ResponseEnvelope;
use TaohuaHub\Ai2\Internal\Http\Transport\GuzzleHttpClient;

final class GuzzleHttpClientTest extends TestCase
{
    public function testGuzzleHttpClientDecodesJsonResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"ret":1,"msg":"success","data":{"ok":true}}'),
        ]);
        $client = new GuzzleHttpClient(new Client(['handler' => HandlerStack::create($mock)]));

        $payload = $client->request('GET', 'https://example.com', '/healthz');

        self::assertSame(1, $payload['ret']);
        self::assertTrue($payload['data']['ok']);
    }

    public function testGuzzleHttpClientWrapsSseResponse(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/event-stream'],
                "event: ack\ndata: {\"session_id\":\"20260413S001\"}\n\n"
            ),
        ]);
        $client = new GuzzleHttpClient(new Client(['handler' => HandlerStack::create($mock)]));

        $payload = $client->request('POST', 'https://example.com', '/admin/system/v1/ai/messages/send');

        self::assertTrue(ResponseEnvelope::isWrapped($payload));
        self::assertSame('text/event-stream', ResponseEnvelope::meta($payload)['content_type']);
    }
}
