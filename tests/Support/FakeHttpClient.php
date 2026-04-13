<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Support;

use TaohuaHub\Ai2\Contract\HttpClientInterface;

final class FakeHttpClient implements HttpClientInterface
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $requests = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $responses;

    /**
     * @param array<int, array<string, mixed>> $responses
     */
    public function __construct(array $responses = [])
    {
        $this->responses = $responses;
    }

    public function request(
        string $method,
        string $baseUri,
        string $path,
        array $headers = [],
        array $query = [],
        ?string $body = null,
        float $timeout = 10.0
    ): array {
        $this->requests[] = [
            'method' => $method,
            'base_uri' => $baseUri,
            'path' => $path,
            'headers' => $headers,
            'query' => $query,
            'body' => $body,
            'timeout' => $timeout,
        ];

        if ($this->responses !== []) {
            return array_shift($this->responses);
        }

        return [
            'ret' => 1,
            'msg' => 'success',
            'data' => [],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function requests(): array
    {
        return $this->requests;
    }

    /**
     * @return array<string, mixed>
     */
    public function lastRequest(): array
    {
        return $this->requests[array_key_last($this->requests)] ?? [];
    }
}
