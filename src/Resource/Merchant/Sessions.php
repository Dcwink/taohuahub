<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\Merchant;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * 商户端会话集合资源。
 *
 * 负责会话创建等集合级操作；会话详情等单实例操作应进入 `session($id)` 上下文。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult create(array $payload) 创建或复用会话
 * @method ApiResult getList(array $query = []) 获取会话列表
 */
final class Sessions
{
    private Dispatcher $dispatcher;

    /**
     * 初始化会话集合资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 创建或复用会话。
     *
     * @param array $payload 创建会话请求体
     * @return ApiResult 会话创建结果
     */
    public function create(array $payload): ApiResult
    {
        return $this->dispatcher->post('/admin/merchant/v1/sessions/create', $payload);
    }

    /**
     * 获取会话列表。
     *
     * @param array $query 查询参数
     * @return ApiResult 会话列表结果
     */
    public function getList(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/merchant/v1/sessions', $query);
    }
}
