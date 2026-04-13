<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Internal\Auth;

/**
 * 商户签名计算器。
 *
 * 签名规则需与服务端 `build_signature` 保持一致，用于 merchant 接口鉴权。
 *
 * 更新时间：2026-04-11
 *
 * @method string sign(string $mchNo, string $sourceSystem, string $timestamp, string $nonce, string $method, string $path, string $body, string $secret) 生成商户签名
 */
final class MerchantSigner
{
    /**
     * 生成商户请求签名。
     *
     * @param string $mchNo 商户号
     * @param string $sourceSystem 来源系统编码
     * @param string $timestamp 时间戳
     * @param string $nonce 随机串
     * @param string $method 请求方法
     * @param string $path 请求路径
     * @param string $body 请求体字符串
     * @param string $secret 商户访问密钥
     * @return string 签名字符串
     */
    public function sign(
        string $mchNo,
        string $sourceSystem,
        string $timestamp,
        string $nonce,
        string $method,
        string $path,
        string $body,
        string $secret
    ): string {
        $payload = implode("\n", [
            $mchNo,
            $sourceSystem,
            $timestamp,
            $nonce,
            strtoupper($method),
            $path,
            hash('sha256', $body),
        ]);

        return hash_hmac('sha256', $payload, $secret);
    }
}
