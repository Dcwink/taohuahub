<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Client;

use Throwable;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Context\System\SessionContext;
use TaohuaHub\Ai2\Contract\HttpClientInterface;
use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Internal\Http\Transport\GuzzleHttpClient;
use TaohuaHub\Ai2\Internal\Http\Transport\UnavailableHttpClient;
use TaohuaHub\Ai2\Resource\System\Agents;
use TaohuaHub\Ai2\Resource\System\Jobs;
use TaohuaHub\Ai2\Resource\System\Messages;
use TaohuaHub\Ai2\Resource\System\Merchants;
use TaohuaHub\Ai2\Resource\System\Models;
use TaohuaHub\Ai2\Resource\System\Ops;
use TaohuaHub\Ai2\Resource\System\PlanPolicies;
use TaohuaHub\Ai2\Resource\System\PlatformAccounts;
use TaohuaHub\Ai2\Resource\System\Platforms;
use TaohuaHub\Ai2\Resource\System\Sessions;
use TaohuaHub\Ai2\Resource\System\SourceSystems;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端客户端入口。
 *
 * 用于组织 admin/system 侧所有资源入口，并负责生成 system 会话上下文对象。
 *
 * 更新时间：2026-04-11
 *
 * @method Sessions sessions() 获取 AI 会话集合资源
 * @method SessionContext session(string $sessionId) 获取单个 AI 会话上下文
 * @method Messages messages() 获取 AI 消息集合资源
 * @method Jobs jobs() 获取 AI 任务集合资源
 * @method Merchants merchants() 获取商户资源
 * @method SourceSystems sourceSystems() 获取来源系统资源
 * @method PlanPolicies planPolicies() 获取套餐策略资源
 * @method Platforms platforms() 获取 AI 平台资源
 * @method PlatformAccounts platformAccounts() 获取平台账号资源
 * @method Models models() 获取模型资源
 * @method Agents agents() 获取 Agent 资源
 * @method Ops ops() 获取运维资源
 * @method ApiResult health() 调用健康检查接口
 * @method string resolveUrl(string $path) 将相对路径解析为完整 URL
 */
final class SystemClient
{
    private Dispatcher $dispatcher;
    private ?Sessions $sessions = null;
    private ?Messages $messages = null;
    private ?Jobs $jobs = null;
    private ?Merchants $merchants = null;
    private ?SourceSystems $sourceSystems = null;
    private ?PlanPolicies $planPolicies = null;
    private ?Platforms $platforms = null;
    private ?PlatformAccounts $platformAccounts = null;
    private ?Models $models = null;
    private ?Agents $agents = null;
    private ?Ops $ops = null;

    /**
     * 初始化 system 端客户端。
     *
     * @param SystemConfig $config system 端配置对象
     * @param HttpClientInterface|null $httpClient 自定义 HTTP 客户端
     */
    public function __construct(SystemConfig $config, ?HttpClientInterface $httpClient = null)
    {
        $this->dispatcher = new Dispatcher($config, $httpClient ?? $this->createDefaultHttpClient());
    }

    /**
     * 获取 AI 会话集合资源。
     *
     * @return Sessions AI 会话集合资源
     */
    public function sessions(): Sessions
    {
        if ($this->sessions === null) {
            $this->sessions = new Sessions($this->dispatcher);
        }

        return $this->sessions;
    }

    /**
     * 获取单个 AI 会话上下文。
     *
     * @param string $sessionId 会话 ID
     * @return SessionContext 会话上下文对象
     */
    public function session(string $sessionId): SessionContext
    {
        return new SessionContext($this->dispatcher, $sessionId);
    }

    /**
     * 获取 AI 消息集合资源。
     *
     * @return Messages AI 消息集合资源
     */
    public function messages(): Messages
    {
        if ($this->messages === null) {
            $this->messages = new Messages($this->dispatcher);
        }

        return $this->messages;
    }

    /**
     * 获取 AI 任务集合资源。
     *
     * @return Jobs AI 任务集合资源
     */
    public function jobs(): Jobs
    {
        if ($this->jobs === null) {
            $this->jobs = new Jobs($this->dispatcher);
        }

        return $this->jobs;
    }

    /**
     * 获取商户资源。
     *
     * @return Merchants 商户资源
     */
    public function merchants(): Merchants
    {
        if ($this->merchants === null) {
            $this->merchants = new Merchants($this->dispatcher);
        }

        return $this->merchants;
    }

    /**
     * 获取来源系统资源。
     *
     * @return SourceSystems 来源系统资源
     */
    public function sourceSystems(): SourceSystems
    {
        if ($this->sourceSystems === null) {
            $this->sourceSystems = new SourceSystems($this->dispatcher);
        }

        return $this->sourceSystems;
    }

    /**
     * 获取套餐策略资源。
     *
     * @return PlanPolicies 套餐策略资源
     */
    public function planPolicies(): PlanPolicies
    {
        if ($this->planPolicies === null) {
            $this->planPolicies = new PlanPolicies($this->dispatcher);
        }

        return $this->planPolicies;
    }

    /**
     * 获取 AI 平台资源。
     *
     * @return Platforms AI 平台资源
     */
    public function platforms(): Platforms
    {
        if ($this->platforms === null) {
            $this->platforms = new Platforms($this->dispatcher);
        }

        return $this->platforms;
    }

    /**
     * 获取平台账号资源。
     *
     * @return PlatformAccounts 平台账号资源
     */
    public function platformAccounts(): PlatformAccounts
    {
        if ($this->platformAccounts === null) {
            $this->platformAccounts = new PlatformAccounts($this->dispatcher);
        }

        return $this->platformAccounts;
    }

    /**
     * 获取模型资源。
     *
     * @return Models 模型资源
     */
    public function models(): Models
    {
        if ($this->models === null) {
            $this->models = new Models($this->dispatcher);
        }

        return $this->models;
    }

    /**
     * 获取 Agent 资源。
     *
     * @return Agents Agent 资源
     */
    public function agents(): Agents
    {
        if ($this->agents === null) {
            $this->agents = new Agents($this->dispatcher);
        }

        return $this->agents;
    }

    /**
     * 获取运维资源。
     *
     * @return Ops 运维资源
     */
    public function ops(): Ops
    {
        if ($this->ops === null) {
            $this->ops = new Ops($this->dispatcher);
        }

        return $this->ops;
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
