<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2;

use TaohuaHub\Ai2\Client\MerchantClient;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;

/**
 * 创建商户端 SDK 客户端。
 *
 * 支持直接传入配置对象，或传入约定格式的数组配置。
 *
 * 更新时间：2026-04-11
 *
 * @param array|MerchantConfig $config 商户端配置
 * @return MerchantClient 商户端客户端实例
 */
function merchant(array|MerchantConfig $config): MerchantClient
{
    if (is_array($config)) {
        $config = MerchantConfig::fromArray($config);
    }

    return new MerchantClient($config);
}

/**
 * 创建 system 端 SDK 客户端。
 *
 * 支持直接传入配置对象，或传入约定格式的数组配置。
 *
 * 更新时间：2026-04-11
 *
 * @param array|SystemConfig $config system 端配置
 * @return SystemClient system 端客户端实例
 */
function system(array|SystemConfig $config): SystemClient
{
    if (is_array($config)) {
        $config = SystemConfig::fromArray($config);
    }

    return new SystemClient($config);
}
