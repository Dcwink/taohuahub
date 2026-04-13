<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Internal\Http\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use TaohuaHub\Ai2\Contract\HttpClientInterface;
use TaohuaHub\Ai2\Exception\TransportException;
use TaohuaHub\Ai2\Internal\Http\ResponseEnvelope;

/**
 * 基于 Guzzle 的 HTTP 客户端实现。
 *
 * 这是 SDK 当前默认的 HTTP 传输实现，后续可按接口替换为其他实现。
 *
 * 更新时间：2026-04-11
 *
 * @method array request(string $method, string $baseUri, string $path, array $headers = [], array $query = [], ?string $body = null, float $timeout = 10.0) 发起 HTTP 请求
 */
final class GuzzleHttpClient implements HttpClientInterface
{
    private Client $client;

    /**
     * 初始化 Guzzle HTTP 客户端。
     *
     * @param Client|null $client 外部注入的 Guzzle 客户端
     */
    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client();
    }

    /**
     * 发起 HTTP 请求并返回解码后的 JSON 数组。
     *
     * @param string $method 请求方法
     * @param string $baseUri 服务基础地址
     * @param string $path 请求路径
     * @param array $headers 请求头
     * @param array $query 查询参数
     * @param string|null $body 请求体字符串
     * @param float $timeout 超时时间
     * @return array 解码后的 JSON 响应
     */
    public function request(
        string $method,
        string $baseUri,
        string $path,
        array $headers = [],
        array $query = [],
        ?string $body = null,
        float $timeout = 10.0
    ): array {
        try {
            $response = $this->client->request($method, $baseUri . $path, [
                'headers' => $headers,
                'query' => $query,
                'body' => $body,
                'timeout' => $timeout,
            ]);
        } catch (GuzzleException $exception) {
            throw new TransportException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }

        $bodyText = (string) $response->getBody();
        $contentType = strtolower(trim(explode(';', $response->getHeaderLine('Content-Type'))[0]));
        if ($contentType === '' || str_contains($contentType, 'json')) {
            $decoded = json_decode($bodyText, true);
            if (!is_array($decoded)) {
                throw new TransportException('响应不是合法 JSON');
            }

            return $decoded;
        }

        return ResponseEnvelope::wrap(
            $response->getStatusCode(),
            $contentType,
            $bodyText,
            $this->flattenHeaders($response->getHeaders())
        );
    }

    /**
     * @param array<string, string[]> $headers
     * @return array<string, string>
     */
    private function flattenHeaders(array $headers): array
    {
        $flattened = [];
        foreach ($headers as $name => $values) {
            $flattened[$name] = implode(', ', $values);
        }

        return $flattened;
    }
}
