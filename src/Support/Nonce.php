<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Support;

/**
 * 随机 nonce 生成工具。
 *
 * 主要用于 merchant 鉴权签名时生成 `X-Nonce`。
 *
 * 更新时间：2026-04-11
 *
 * @method static string generate() 生成随机 nonce
 */
final class Nonce
{
    /**
     * 生成随机 nonce 字符串。
     *
     * @return string 随机 nonce
     */
    public static function generate(): string
    {
        return bin2hex(random_bytes(8));
    }
}
