<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Internal\Http;

/**
 * 非 JSON HTTP 响应包裹器。
 *
 * 用于在不修改 HttpClientInterface 返回签名的前提下，携带原始响应元数据。
 */
final class ResponseEnvelope
{
    public const META_KEY = '__sdk_response_meta';

    /**
     * @param array<string, string> $headers
     * @return array<string, mixed>
     */
    public static function wrap(int $statusCode, string $contentType, string $body, array $headers = []): array
    {
        return [
            self::META_KEY => [
                'status_code' => $statusCode,
                'content_type' => $contentType,
                'body' => $body,
                'headers' => $headers,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function isWrapped(array $payload): bool
    {
        return isset($payload[self::META_KEY]) && is_array($payload[self::META_KEY]);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public static function meta(array $payload): array
    {
        return self::isWrapped($payload) ? $payload[self::META_KEY] : [];
    }
}
