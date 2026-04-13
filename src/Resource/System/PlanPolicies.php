<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端套餐策略资源。
 *
 * 提供套餐策略列表、详情及与服务端对齐的占位管理接口。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult getList(array $query = []) 获取套餐策略列表
 * @method ApiResult get(string $planCode) 获取套餐策略详情
 * @method ApiResult create(array $payload) 创建套餐策略
 * @method ApiResult update(array $payload) 更新套餐策略
 * @method ApiResult updateStatus(array $payload) 更新套餐策略状态
 * @method ApiResult delete(array $payload) 删除套餐策略
 */
final class PlanPolicies
{
    private Dispatcher $dispatcher;

    /**
     * 初始化套餐策略资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 获取套餐策略列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/plan-policies', $query);
    }

    /**
     * 获取套餐策略详情。
     *
     * @param string $planCode 套餐编码
     * @return ApiResult 详情结果
     */
    public function get(string $planCode): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/plan-policies/detail', [
            'plan_code' => $planCode,
        ]);
    }

    /**
     * 创建套餐策略。
     *
     * @param array $payload 创建请求体
     * @return ApiResult 创建结果
     */
    public function create(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/plan-policies/create', $payload);
    }

    /**
     * 更新套餐策略。
     *
     * @param array $payload 更新请求体
     * @return ApiResult 更新结果
     */
    public function update(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/plan-policies/update', $payload);
    }

    /**
     * 更新套餐策略状态。
     *
     * @param array $payload 状态更新请求体
     * @return ApiResult 更新结果
     */
    public function updateStatus(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/plan-policies/status', $payload);
    }

    /**
     * 删除套餐策略。
     *
     * @param array $payload 删除请求体
     * @return ApiResult 删除结果
     */
    public function delete(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/plan-policies/delete', $payload);
    }
}
