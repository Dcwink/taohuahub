<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Resource\System;

use TaohuaHub\Ai2\Internal\Http\Dispatcher;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * system 端运维资源。
 *
 * 提供队列、Worker、Provider 健康、失败任务和链路追踪查询能力。
 */
final class Ops
{
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function queueStatus(): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ops/queue-status');
    }

    public function workers(): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ops/workers');
    }

    public function providerHealth(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ops/provider-health', $query);
    }

    public function failedJobs(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ops/failed-jobs', $query);
    }

    public function trace(array $query = []): ApiResult
    {
        return $this->dispatcher->get('/admin/system/v1/ops/trace', $query);
    }
}
