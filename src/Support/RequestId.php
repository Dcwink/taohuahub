<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Support;

/**
 * 请求 ID 生成工具。
 *
 * 用于为 system / merchant 请求统一生成 `X-Request-Id`。
 *
 * 更新时间：2026-04-11
 *
 * @method static string generate(string $prefix = 'req') 生成请求 ID
 */
final class RequestId
{
    /**
     * 生成请求 ID。
     *
     * @param string $prefix 请求 ID 前缀
     * @return string 随机请求 ID
     */
    public static function generate(string $prefix = 'req'): string
    {
        $prefix = trim($prefix) !== '' ? trim($prefix) : 'req';

        return sprintf(
            '%s_%s_%s',
            $prefix,
            date('YmdHis'),
            bin2hex(random_bytes(4))
        );
    }
}
