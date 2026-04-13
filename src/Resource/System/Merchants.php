<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端商户资源。
 *
 * 提供商户的创建、查询、更新、状态管理、余额调整和流水查询能力。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult create(array $payload) 创建商户
 * @method ApiResult getList(array $query = []) 获取商户列表
 * @method ApiResult get(string $mchNo) 获取商户详情
 * @method ApiResult update(array $payload) 更新商户
 * @method ApiResult updateStatus(array $payload) 更新商户状态
 * @method ApiResult updateBalance(array $payload) 更新商户余额
 * @method ApiResult delete(array $payload) 删除商户
 * @method ApiResult ledger(array $query = []) 获取商户流水列表
 * @method ApiResult ledgerDetail(string $ledgerId) 获取商户流水详情
 */
final class Merchants
{
    private Dispatcher $dispatcher;

    /**
     * 初始化商户资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 创建商户。
     *
     * @param array $payload 创建商户请求体
     * @return ApiResult 创建结果
     */
    public function create(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/merchants/create', $payload);
    }

    /**
     * 获取商户列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 商户列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/merchants', $query);
    }

    /**
     * 获取商户详情。
     *
     * @param string $mchNo 商户号
     * @return ApiResult 商户详情结果
     */
    public function get(string $mchNo): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/merchants/detail', [
            'mchno' => $mchNo,
        ]);
    }

    /**
     * 更新商户信息。
     *
     * @param array $payload 更新商户请求体
     * @return ApiResult 更新结果
     */
    public function update(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/merchants/update', $payload);
    }

    /**
     * 更新商户状态。
     *
     * @param array $payload 更新状态请求体
     * @return ApiResult 更新结果
     */
    public function updateStatus(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/merchants/status', $payload);
    }

    /**
     * 更新商户余额或信用额度。
     *
     * @param array $payload 余额更新请求体
     * @return ApiResult 更新结果
     */
    public function updateBalance(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/merchants/balance', $payload);
    }

    /**
     * 删除商户。
     *
     * @param array $payload 删除商户请求体
     * @return ApiResult 删除结果
     */
    public function delete(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/merchants/delete', $payload);
    }

    /**
     * 获取商户流水列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 商户流水列表结果
     */
    public function ledger(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/merchants/ledger', $query);
    }

    /**
     * 获取商户流水详情。
     *
     * @param string $ledgerId 流水 ID
     * @return ApiResult 商户流水详情结果
     */
    public function ledgerDetail(string $ledgerId): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/merchants/ledger/detail', [
            'ledger_id' => $ledgerId,
        ]);
    }
}
