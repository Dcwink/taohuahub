<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端 AI 消息集合资源。
 *
 * 适用于不依赖会话上下文直接按条件查询消息，或按消息 ID 直接获取详情。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult getList(array $query = []) 获取消息列表
 * @method ApiResult get(string $messageId, bool $includeArchive = false) 获取消息详情
 * @method ApiResult send(array $payload) 发送消息
 */
final class Messages
{
    private Dispatcher $dispatcher;

    /**
     * 初始化 AI 消息集合资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 获取消息列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 消息列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ai/messages', $query);
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
        return $this->dispatcher->get('/admin/system/v1/ai/messages/detail', [
            'message_id' => $messageId,
            'include_archive' => $includeArchive,
        ]);
    }

    /**
     * 发送消息。
     *
     * @param array $payload 发送消息请求体
     * @return ApiResult 发送结果
     */
    public function send(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/ai/messages/send', $payload);
    }
}
