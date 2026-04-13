# TaohuaHub AI2 PHP SDK

PHP 8 SDK for the TaohuaHub AI2 project.

Packagist:

- `taohuahub/ai2-sdk-php`
- https://packagist.org/packages/taohuahub/ai2-sdk-php

## 安装

```bash
composer require taohuahub/ai2-sdk-php
```

如需安装指定稳定版本：

```bash
composer require taohuahub/ai2-sdk-php:^0.1
```

## 运行要求

- PHP `^8.0`
- `ext-json`

## 快速开始

### Merchant 客户端

```php
<?php

use TaohuaHub\Ai2\Config\MerchantConfig;

$merchant = \TaohuaHub\Ai2\merchant(
    new MerchantConfig(
        'https://example.com',
        'MCH202604110001',
        'default',
        'merchant-access-secret'
    )
);

$newSession = $merchant->sessions()->create([
    'chat_mode' => 'model',
    'actor_type' => 'user',
    'actor_id' => '10001',
    'isnew' => true,
    'model_id' => 1,
]);

$session = $merchant->session('20260411S0A1B2C3D4E');
$sessionInfo = $session->get();
$messages = $session->messages();
$messageList = $messages->getList(['page' => 1]);
$messageInfo = $merchant->messages()->get('20260411M0A1B2C3D4E');
$jobs = $session->jobs()->getList(['page' => 1]);
$health = $merchant->health();

$signed = $session->sseSign()->data();
$absoluteSseUrl = $merchant->resolveUrl($signed['sse_url'] ?? '');
```

### System 客户端

```php
<?php

use TaohuaHub\Ai2\Config\SystemConfig;

$system = \TaohuaHub\Ai2\system(
    new SystemConfig('https://example.com', 'system-api-key')
);

$queue = $system->ops()->queueStatus();
$workers = $system->ops()->workers();
$providerHealth = $system->ops()->providerHealth(['limit' => 20]);
```

## 流式说明

- merchant 侧仍通过 `sseSign()` 获取签名地址后自行消费 SSE
- 使用 `sseSign()` 获取签名地址
- 使用 `resolveUrl()` 将相对 `sse_url` 转成完整 URL
- `system->messages()->send(['response_mode' => 'stream', ...])` 当前会等待流结束，并将 SSE 事件解析到返回结果 `data()['events']`

## 已提供资源

### Merchant

- `sessions()`
- `session($sessionId)`
- `messages()`
- `jobs()`
- `stats()`

### System

- `sessions()`
- `session($sessionId)`
- `messages()`
- `jobs()`
- `merchants()`
- `sourceSystems()`
- `planPolicies()`
- `platforms()`
- `platformAccounts()`
- `models()`
- `agents()`
- `ops()`

## 接口文档

- `docs/README.md`
- `docs/common/01_客户端入口与配置.md`
- `docs/merchant/README.md`
- `docs/system/README.md`

## 开发与发布

- 代码仓库会由 Packagist 自动同步新 tag
- 推荐通过 Git tag 发布稳定版本，例如 `v0.1.1`
- `main` 分支会在 Packagist 中显示为开发版本 `dev-main`
