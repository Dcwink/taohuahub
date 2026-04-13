<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端 AI 会话集合资源。
 *
 * 负责 AI 会话创建等集合级操作；单实例操作应进入 `session($id)` 上下文。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult create(array $payload) 创建或复用 AI 会话
 * @method ApiResult getList(array $query = []) 获取 AI 会话列表（当前仅保留设计入口）
 */
final class Sessions
{
    private Dispatcher $dispatcher;

    /**
     * 初始化 AI 会话集合资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 创建或复用 AI 会话。
     *
     * @param array $payload 创建会话请求体
     * @return ApiResult 会话创建结果
     */
    public function create(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/system/v1/ai/sessions/create', $payload);
    }

    /**
     * 获取 AI 会话列表。
     *
     * 当前服务端尚未提供该接口，因此这里返回统一失败结果，而不是直接抛异常。
     *
     * @param array $query 查询参数
     * @return ApiResult 会话列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return ApiResult::failure(
            '接口暂未实现',
            'NOT_IMPLEMENTED',
            '当前服务端未提供 system session list 接口，该方法仅保留为 SDK 规划入口。'
        );
    }
}
