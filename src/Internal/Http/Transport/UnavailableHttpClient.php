<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Internal\Http\Transport;

use TaohuaHub\Ai2\Contract\HttpClientInterface;
use TaohuaHub\Ai2\Exception\TransportException;

/**
 * 不可用的 HTTP 客户端占位实现。
 *
 * 当默认 HTTP 依赖无法实例化时，使用该实现延迟失败，并由 Dispatcher 统一收口为 ApiResult。
 *
 * 更新时间：2026-04-11
 *
 * @method array request(string $method, string $baseUri, string $path, array $headers = [], array $query = [], ?string $body = null, float $timeout = 10.0) 发起 HTTP 请求
 */
final class UnavailableHttpClient implements HttpClientInterface
{
    private string $reason;

    /**
     * 初始化不可用 HTTP 客户端。
     *
     * @param string $reason 不可用原因
     */
    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    /**
     * 直接抛出传输异常，由上层统一转换为失败结果。
     *
     * @param string $method 请求方法
     * @param string $baseUri 服务基础地址
     * @param string $path 请求路径
     * @param array $headers 请求头
     * @param array $query 查询参数
     * @param string|null $body 请求体
     * @param float $timeout 超时时间
     * @return array 不会实际返回
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
        throw new TransportException($this->reason);
    }
}
