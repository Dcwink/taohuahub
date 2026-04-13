# Merchant 接口总览

## 资源入口

- `sessions()`
- `session($sessionId)`
- `messages()`
- `jobs()`
- `stats()`

## 文档分组

- [会话接口](./01_会话接口.md)
- [消息接口](./02_消息接口.md)
- [任务接口](./03_任务接口.md)
- [统计接口](./04_统计接口.md)

## 调用风格

```php
<?php

use TaohuaHub\Ai2\Factory;

$merchant = Factory::merchant($config);

$session = $merchant->session('20260411S0A1B2C3D4E');
$session->get();
$session->messages()->send([
    'request_id' => 'req_001',
    'sender_actor_type' => 'user',
    'sender_actor_id' => '10001',
    'content' => 'hello',
]);
```
