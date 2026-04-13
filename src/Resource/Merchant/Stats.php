<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\Merchant;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * 商户端统计资源。
 *
 * 提供会话统计和商户维度统计查询。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult session(string $sessionId) 获取会话统计
 * @method ApiResult merchant(array $query = []) 获取商户统计
 */
final class Stats
{
    private Dispatcher $dispatcher;

    /**
     * 初始化统计资源。
     *
     * @param Dispatcher $dispatcher 统一调度器
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * 获取会话统计。
     *
     * @param string $sessionId 会话 ID
     * @return ApiResult 会话统计结果
     */
    public function session(string $sessionId): ApiResult
    {
        return $this->dispatcher->get('/admin/merchant/v1/stats/session', [
            'session_id' => $sessionId,
        ]);
    }

    /**
     * 获取商户维度统计。
     *
     * @param array $query 查询参数
     * @return ApiResult 商户统计结果
     */
    public function merchant(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/merchant/v1/stats/merchant', $query);
    }
}
