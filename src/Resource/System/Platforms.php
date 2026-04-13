<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端 AI 平台资源。
 *
 * 提供 AI 平台列表和详情查询。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult getList(array $query = []) 获取平台列表
 * @method ApiResult get(string $platformCode) 获取平台详情
 */
final class Platforms
{
    private Dispatcher $dispatcher;

    /**
     * 初始化 AI 平台资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 获取平台列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 平台列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/platforms', $query);
    }

    /**
     * 获取平台详情。
     *
     * @param string $platformCode 平台编码
     * @return ApiResult 平台详情结果
     */
    public function get(string $platformCode): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/platforms/detail', [
            'platform_code' => $platformCode,
        ]);
    }
}
