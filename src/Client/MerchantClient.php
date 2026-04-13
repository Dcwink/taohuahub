<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Client;

use Throwable;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Context\Merchant\SessionContext;
use TaohuaHub\Ai2\Contract\HttpClientInterface;
use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Internal\Http\Transport\GuzzleHttpClient;
use TaohuaHub\Ai2\Internal\Http\Transport\UnavailableHttpClient;
use TaohuaHub\Ai2\Resource\Merchant\Jobs;
use TaohuaHub\Ai2\Resource\Merchant\Messages;
use TaohuaHub\Ai2\Resource\Merchant\Sessions;
use TaohuaHub\Ai2\Resource\Merchant\Stats;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * 商户端客户端入口。
 *
 * 用于组织 merchant 端所有资源入口，并负责生成会话上下文对象。
 *
 * 更新时间：2026-04-11
 *
 * @method Sessions sessions() 获取会话集合资源
 * @method SessionContext session(string $sessionId) 获取单个会话上下文
 * @method Messages messages() 获取消息集合资源
 * @method Jobs jobs() 获取任务集合资源
 * @method Stats stats() 获取统计资源
 * @method ApiResult health() 调用健康检查接口
 * @method string resolveUrl(string $path) 将相对路径解析为完整 URL
 */
final class MerchantClient
{
    private Dispatcher $dispatcher;
    private ?Sessions $sessions = null;
    private ?Messages $messages = null;
    private ?Jobs $jobs = null;
    private ?Stats $stats = null;

    /**
     * 初始化商户端客户端。
     *
     * @param MerchantConfig $config 商户端配置对象
     * @param HttpClientInterface|null $httpClient 自定义 HTTP 客户端
     */
    public function __construct(MerchantConfig $config, ?HttpClientInterface $httpClient = null)
    {
        $this->dispatcher = new Dispatcher($config, $httpClient ?? $this->createDefaultHttpClient());
    }

    /**
     * 获取会话集合资源。
     *
     * @return Sessions 会话集合资源
     */
    public function sessions(): Sessions
    {
        if ($this->sessions === null) {
            $this->sessions = new Sessions($this->dispatcher);
        }

        return $this->sessions;
    }

    /**
     * 获取单个会话上下文。
     *
     * @param string $sessionId 会话 ID
     * @return SessionContext 会话上下文对象
     */
    public function session(string $sessionId): SessionContext
    {
        return new SessionContext($this->dispatcher, $sessionId);
    }

    /**
     * 获取消息集合资源。
     *
     * @return Messages 消息集合资源
     */
    public function messages(): Messages
    {
        if ($this->messages === null) {
            $this->messages = new Messages($this->dispatcher);
        }

        return $this->messages;
    }

    /**
     * 获取任务集合资源。
     *
     * @return Jobs 任务集合资源
     */
    public function jobs(): Jobs
    {
        if ($this->jobs === null) {
            $this->jobs = new Jobs($this->dispatcher);
        }

        return $this->jobs;
    }

    /**
     * 获取统计资源。
     *
     * @return Stats 统计资源
     */
    public function stats(): Stats
    {
        if ($this->stats === null) {
            $this->stats = new Stats($this->dispatcher);
        }

        return $this->stats;
    }

    /**
     * 调用健康检查接口。
     *
     * @return array 健康检查原始响应
     */
    public function health(): ApiResult
    {
        return $this->dispatcher->rawGet('/healthz');
    }

    /**
     * 将相对路径解析为完整 URL。
     *
     * @param string $path 相对路径
     * @return string 完整 URL
     */
    public function resolveUrl(string $path): string
    {
        return $this->dispatcher->resolveUrl($path);
    }

    /**
     * 创建默认 HTTP 客户端。
     *
     * 若默认依赖不可用，则返回占位客户端，由后续调用统一转换为失败结果。
     *
     * @return HttpClientInterface HTTP 客户端实现
     */
    private function createDefaultHttpClient(): HttpClientInterface
    {
        try {
            return new GuzzleHttpClient();
        } catch (Throwable $throwable) {
            return new UnavailableHttpClient('默认 HTTP 客户端不可用：' . $throwable->getMessage());
        }
    }
}
