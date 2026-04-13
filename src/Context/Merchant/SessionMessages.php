<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Context\Merchant;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * 商户端会话消息上下文。
 *
 * 用于围绕某个固定会话执行消息列表、消息详情和发送消息操作。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult getList(array $query = []) 获取当前会话消息列表
 * @method ApiResult get(string $messageId, bool $includeArchive = false) 获取消息详情
 * @method ApiResult send(array $payload) 向当前会话发送消息
 */
final class SessionMessages
{
    private Dispatcher $dispatcher;
    private string $sessionId;

    /**
     * 初始化会话消息上下文。
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
     * 获取当前会话的消息列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 消息列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/merchant/v1/messages', [
            's_session_id' => $this->sessionId,
        ] + $query);
    }

    /**
     * 获取消息详情。
     *
     * @param string $messageId 消息 ID
     * @param bool $includeArchive 是否包含归档消息
     * @return ApiResult 消息详情结果
     */
    public function get(string $messageId, bool $includeArchive = false): ApiResult
    {
        return $this->dispatcher->get('/admin/merchant/v1/messages/detail', [
            'message_id' => $messageId,
            'include_archive' => $includeArchive,
        ]);
    }

    /**
     * 向当前会话发送消息。
     *
     * @param array $payload 发送消息请求体
     * @return ApiResult 发送结果
     */
    public function send(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/merchant/v1/messages/send', [
            'session_id' => $this->sessionId,
        ] + $payload);
    }
}
