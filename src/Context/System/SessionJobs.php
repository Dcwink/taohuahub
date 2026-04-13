<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Context\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端会话任务上下文。
 *
 * 用于围绕某个固定会话筛选和处理相关 AI 任务。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult getList(array $query = []) 获取当前会话任务列表
 * @method ApiResult get(string $jobId) 获取任务详情
 * @method ApiResult cancel(array $payload) 取消任务
 */
final class SessionJobs
{
    private Dispatcher $dispatcher;
    private string $sessionId;

    /**
     * 初始化会话任务上下文。
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
     * 获取当前会话任务列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 任务列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ai/jobs', [
            's_session_id' => $this->sessionId,
        ] + $query);
    }

    /**
     * 获取任务详情。
     *
     * @param string $jobId 任务 ID
     * @return ApiResult 任务详情结果
     */
    public function get(string $jobId): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ai/jobs/detail', [
            'job_id' => $jobId,
        ]);
    }

    /**
     * 取消任务。
     *
     * @param array $payload 取消任务请求参数
     * @return ApiResult 取消结果
     */
    public function cancel(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/ai/jobs/cancel', $payload);
    }
}
