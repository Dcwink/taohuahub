# TaohuaHub AI2 PHP SDK

PHP 8 SDK skeleton for the TaohuaHub AI2 project.

Current focus:

- lock in long-term directory structure
- lock in public calling style
- isolate internal dispatcher/auth/signature layers
- map current server APIs into SDK resources

Planned public entry style:

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

System 运维接口：

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

流式说明：

- merchant 侧仍通过 `sseSign()` 获取签名地址后自行消费 SSE
- 使用 `sseSign()` 获取签名地址
- 使用 `resolveUrl()` 将相对 `sse_url` 转成完整 URL
- `system->messages()->send(['response_mode' => 'stream', ...])` 当前会等待流结束，并将 SSE 事件解析到返回结果 `data()['events']`

See:

- `docs/01_架构设计.md`
- `docs/02_目录结构.md`
- `docs/03_API规划.md`
