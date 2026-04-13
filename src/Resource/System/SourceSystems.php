<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端来源系统资源。
 *
 * 提供来源系统的创建、查询、更新、状态管理和删除能力。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult create(array $payload) 创建来源系统
 * @method ApiResult getList(array $query = []) 获取来源系统列表
 * @method ApiResult get(string $mchNo, string $sourceSystemCode) 获取来源系统详情
 * @method ApiResult update(array $payload) 更新来源系统
 * @method ApiResult updateStatus(array $payload) 更新来源系统状态
 * @method ApiResult delete(array $payload) 删除来源系统
 */
final class SourceSystems
{
    private Dispatcher $dispatcher;

    /**
     * 初始化来源系统资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 创建来源系统。
     *
     * @param array $payload 创建来源系统请求体
     * @return ApiResult 创建结果
     */
    public function create(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/source-systems/create', $payload);
    }

    /**
     * 获取来源系统列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/source-systems', $query);
    }

    /**
     * 获取来源系统详情。
     *
     * @param string $mchNo 商户号
     * @param string $sourceSystemCode 来源系统编码
     * @return ApiResult 详情结果
     */
    public function get(string $mchNo, string $sourceSystemCode): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/source-systems/detail', [
            'mchno' => $mchNo,
            'source_system_code' => $sourceSystemCode,
        ]);
    }

    /**
     * 更新来源系统。
     *
     * @param array $payload 更新请求体
     * @return ApiResult 更新结果
     */
    public function update(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/source-systems/update', $payload);
    }

    /**
     * 更新来源系统状态。
     *
     * @param array $payload 更新状态请求体
     * @return ApiResult 更新结果
     */
    public function updateStatus(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/source-systems/status', $payload);
    }

    /**
     * 删除来源系统。
     *
     * @param array $payload 删除请求体
     * @return ApiResult 删除结果
     */
    public function delete(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/source-systems/delete', $payload);
    }
}
