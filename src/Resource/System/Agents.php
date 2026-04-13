<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端 Agent 资源。
 *
 * 提供 Agent 的创建、校验、查询、更新、状态管理和删除能力。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult create(array $payload) 创建 Agent
 * @method ApiResult check(array $payload) 校验 Agent
 * @method ApiResult checkBatch(array $payload) 批量校验 Agent
 * @method ApiResult getList(array $query = []) 获取 Agent 列表
 * @method ApiResult get(int $agentId) 获取 Agent 详情
 * @method ApiResult update(array $payload) 更新 Agent
 * @method ApiResult updateStatus(array $payload) 更新 Agent 状态
 * @method ApiResult delete(array $payload) 删除 Agent
 */
final class Agents
{
    private Dispatcher $dispatcher;

    /**
     * 初始化 Agent 资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 创建 Agent。
     *
     * @param array $payload 创建请求体
     * @return ApiResult 创建结果
     */
    public function create(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/agents/create', $payload);
    }

    /**
     * 校验 Agent 可用性。
     *
     * @param array $payload 校验请求体
     * @return ApiResult 校验结果
     */
    public function check(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/agents/check', $payload);
    }

    /**
     * 批量校验 Agent 可用性。
     *
     * @param array $payload 批量校验请求体
     * @return ApiResult 校验结果
     */
    public function checkBatch(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/agents/check-batch', $payload);
    }

    /**
     * 获取 Agent 列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/agents', $query);
    }

    /**
     * 获取 Agent 详情。
     *
     * @param int $agentId Agent 主键 ID
     * @return ApiResult 详情结果
     */
    public function get(int $agentId): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/agents/detail', [
            'agent_id' => $agentId,
        ]);
    }

    /**
     * 更新 Agent。
     *
     * @param array $payload 更新请求体
     * @return ApiResult 更新结果
     */
    public function update(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/agents/update', $payload);
    }

    /**
     * 更新 Agent 状态。
     *
     * @param array $payload 状态更新请求体
     * @return ApiResult 更新结果
     */
    public function updateStatus(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/agents/status', $payload);
    }

    /**
     * 删除 Agent。
     *
     * @param array $payload 删除请求体
     * @return ApiResult 删除结果
     */
    public function delete(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/agents/delete', $payload);
    }
}
