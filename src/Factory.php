<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2;

use TaohuaHub\Ai2\Client\MerchantClient;
use TaohuaHub\Ai2\Client\SystemClient;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;

final class Factory
{
    public static function merchant(array|MerchantConfig $config): MerchantClient
    {
        if (is_array($config)) {
            $config = MerchantConfig::fromArray($config);
        }

        return new MerchantClient($config);
    }

    public static function system(array|SystemConfig $config): SystemClient
    {
        if (is_array($config)) {
            $config = SystemConfig::fromArray($config);
        }

        return new SystemClient($config);
    }
}
