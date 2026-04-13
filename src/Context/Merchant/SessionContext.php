<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Context\Merchant;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * 商户端单会话上下文。
 *
 * 负责围绕某个固定 `session_id` 组织会话详情、关闭、续签、切换模型及其子资源访问。
 *
 * 更新时间：2026-04-11
 *
 * @method string id() 获取当前会话 ID
 * @method ApiResult get() 获取当前会话详情
 * @method ApiResult close(array $payload = []) 关闭当前会话
 * @method ApiResult sseSign(array $payload = []) 续签当前会话的 SSE 订阅地址
 * @method ApiResult switchModel(array $payload) 切换当前会话模型
 * @method SessionMessages messages() 获取当前会话消息上下文
 * @method SessionJobs jobs() 获取当前会话任务上下文
 * @method ApiResult stats() 获取当前会话统计
 */
final class SessionContext
{
    private Dispatcher $dispatcher;
    private string $sessionId;

    /**
     * 初始化商户端会话上下文。
     *
     * @param Dispatcher $dispatcher 统一调度器
     * @param string $sessionId 会话 ID
     */
    public function __construct(Dispatcher $dispatcher, string $sessionId)
    {
        $this->dispatcher = $dispatcher;
        $this->sessionId = $sessionId;
    }

    /**
     * 获取当前会话 ID。
     *
     * @return string 会话 ID
     */
    public function id(): string
    {
        return $this->sessionId;
    }

    /**
     * 获取当前会话详情。
     *
     * @return ApiResult 会话详情结果
     */
    public function get(): ApiResult
    {
        return $this->dispatcher->get('/admin/merchant/v1/sessions/detail', [
            'session_id' => $this->sessionId,
        ]);
    }

    /**
     * 关闭当前会话。
     *
     * @param array $payload 附加请求参数
     * @return ApiResult 关闭结果
     */
    public function close(array $payload = []): ApiResult
    {
        return $this->dispatcher->post('/admin/merchant/v1/sessions/close', [
            'session_id' => $this->sessionId,
        ] + $payload);
    }

    /**
     * 续签当前会话的 SSE 地址。
     *
     * @param array $payload 附加请求参数
     * @return ApiResult SSE 签名结果
     */
    public function sseSign(array $payload = []): ApiResult
    {
        return $this->dispatcher->post('/admin/merchant/v1/sessions/sse-sign', [
            'session_id' => $this->sessionId,
        ] + $payload);
    }

    /**
     * 切换当前会话的模型。
     *
     * @param array $payload 切换模型请求参数
     * @return ApiResult 切换结果
     */
    public function switchModel(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/merchant/v1/sessions/switch-model', [
            'session_id' => $this->sessionId,
        ] + $payload);
    }

    /**
     * 获取当前会话消息上下文。
     *
     * @return SessionMessages 会话消息上下文
     */
    public function messages(): SessionMessages
    {
        return new SessionMessages($this->dispatcher, $this->sessionId);
    }

    /**
     * 获取当前会话任务上下文。
     *
     * @return SessionJobs 会话任务上下文
     */
    public function jobs(): SessionJobs
    {
        return new SessionJobs($this->dispatcher, $this->sessionId);
    }

    /**
     * 获取当前会话统计信息。
     *
     * @return ApiResult 会话统计结果
     */
    public function stats(): ApiResult
    {
        return $this->dispatcher->get('/admin/merchant/v1/stats/session', [
            'session_id' => $this->sessionId,
        ]);
    }
}
