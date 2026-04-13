<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Internal\Auth;

use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Support\Nonce;
use TaohuaHub\Ai2\Support\RequestId;

/**
 * 请求头构建器。
 *
 * 负责根据当前客户端类型构建 system 或 merchant 所需的请求头。
 *
 * 更新时间：2026-04-11
 *
 * @method array buildForMerchant(MerchantConfig $config, string $method, string $path, string $body) 构建商户端请求头
 * @method array buildForSystem(SystemConfig $config) 构建 system 端请求头
 */
final class HeaderBuilder
{
    private MerchantSigner $merchantSigner;

    /**
     * 初始化请求头构建器。
     *
     * @param MerchantSigner|null $merchantSigner 商户签名计算器
     */
    public function __construct(?MerchantSigner $merchantSigner = null)
    {
        $this->merchantSigner = $merchantSigner ?? new MerchantSigner();
    }

    /**
     * 构建 merchant 请求头。
     *
     * @param MerchantConfig $config 商户配置
     * @param string $method 请求方法
     * @param string $path 请求路径
     * @param string $body 请求体
     * @return array 构建后的请求头
     */
    public function buildForMerchant(
        MerchantConfig $config,
        string $method,
        string $path,
        string $body
    ): array {
        $timestamp = (string) time();
        $nonce = Nonce::generate();
        $requestId = RequestId::generate($config->requestIdPrefix);
        $signature = $this->merchantSigner->sign(
            $config->mchNo,
            $config->sourceSystem,
            $timestamp,
            $nonce,
            $method,
            $path,
            $body,
            $config->accessSecret
        );

        return [
            'Content-Type' => 'application/json',
            'X-Mch-No' => $config->mchNo,
            'X-Source-System' => $config->sourceSystem,
            'X-Timestamp' => $timestamp,
            'X-Nonce' => $nonce,
            'X-Signature' => $signature,
            'X-Request-Id' => $requestId,
        ];
    }

    /**
     * 构建 system 请求头。
     *
     * @param SystemConfig $config system 配置
     * @return array 构建后的请求头
     */
    public function buildForSystem(SystemConfig $config): array
    {
        return [
            'Content-Type' => 'application/json',
            'X-System-Key' => $config->systemKey,
            'X-Request-Id' => RequestId::generate($config->requestIdPrefix),
        ];
    }
}
