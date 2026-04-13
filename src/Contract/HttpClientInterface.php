<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Contract;

/**
 * HTTP 客户端抽象接口。
 *
 * 用于解耦具体的 HTTP 实现，便于后续替换 Guzzle 或注入测试客户端。
 *
 * 更新时间：2026-04-11
 */
interface HttpClientInterface
{
    /**
     * 发起一次 HTTP 请求。
     *
     * @param string $method 请求方法
     * @param string $baseUri 服务基础地址
     * @param string $path 请求路径
     * @param array $headers 请求头
     * @param array $query 查询参数
     * @param string|null $body 请求体
     * @param float $timeout 超时时间
     * @return array 解码后的响应数组
     */
    public function request(
        string $method,
        string $baseUri,
        string $path,
        array $headers = [],
        array $query = [],
        ?string $body = null,
        float $timeout = 10.0
    ): array;
}
