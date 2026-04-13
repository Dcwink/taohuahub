<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端平台账号资源。
 *
 * 提供平台账号的创建、查询、更新、余额调整、状态管理和删除能力。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult create(array $payload) 创建平台账号
 * @method ApiResult getList(array $query = []) 获取平台账号列表
 * @method ApiResult get(string $accountCode) 获取平台账号详情
 * @method ApiResult update(array $payload) 更新平台账号
 * @method ApiResult updateBalance(array $payload) 更新平台账号余额
 * @method ApiResult updateStatus(array $payload) 更新平台账号状态
 * @method ApiResult delete(array $payload) 删除平台账号
 */
final class PlatformAccounts
{
    private Dispatcher $dispatcher;

    /**
     * 初始化平台账号资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 创建平台账号。
     *
     * @param array $payload 创建请求体
     * @return ApiResult 创建结果
     */
    public function create(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/platform-accounts/create', $payload);
    }

    /**
     * 获取平台账号列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/platform-accounts', $query);
    }

    /**
     * 获取平台账号详情。
     *
     * @param string $accountCode 平台账号编码
     * @return ApiResult 详情结果
     */
    public function get(string $accountCode): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/platform-accounts/detail', [
            'account_code' => $accountCode,
        ]);
    }

    /**
     * 更新平台账号。
     *
     * @param array $payload 更新请求体
     * @return ApiResult 更新结果
     */
    public function update(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/platform-accounts/update', $payload);
    }

    /**
     * 更新平台账号余额。
     *
     * @param array $payload 余额更新请求体
     * @return ApiResult 更新结果
     */
    public function updateBalance(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/platform-accounts/balance', $payload);
    }

    /**
     * 更新平台账号状态。
     *
     * @param array $payload 状态更新请求体
     * @return ApiResult 更新结果
     */
    public function updateStatus(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/platform-accounts/status', $payload);
    }

    /**
     * 删除平台账号。
     *
     * @param array $payload 删除请求体
     * @return ApiResult 删除结果
     */
    public function delete(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/platform-accounts/delete', $payload);
    }
}
