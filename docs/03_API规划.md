# AI2 PHP SDK API 规划

## 1. 公开调用风格

### Merchant

```php
$merchant = \TaohuaHub\Ai2\merchant($config);

$merchant->sessions()->create($data);

$session = $merchant->session($sessionId);
$session->get();
$session->close($data);
$session->sseSign($data);
$session->switchModel($data);

$messages = $session->messages();
$messages->getList($query);
$messages->get($messageId);
$messages->send($data);

$merchant->messages()->getList($query);
$merchant->messages()->get($messageId);
$merchant->messages()->send($data);

$jobs = $session->jobs();
$jobs->getList($query);
$jobs->get($jobId);
$jobs->cancel($data);

$merchant->jobs()->getList($query);
$merchant->jobs()->get($jobId);
$merchant->jobs()->cancel($data);

$session->stats();
$merchant->stats()->merchant($query);
```

### System

```php
$system = \TaohuaHub\Ai2\system($config);

$system->sessions()->create($data);

$session = $system->session($sessionId);
$session->get();
$session->close($data);
$session->sseSign($data);
$session->switchModel($data);
$session->messages()->getList($query);
$session->messages()->get($messageId);
$session->messages()->send($data);

$system->messages()->getList($query);
$system->messages()->get($messageId);
$system->messages()->send($data);
$session->jobs()->getList($query);
$session->jobs()->get($jobId);
$session->jobs()->cancel($data);

$system->jobs()->getList($query);
$system->jobs()->get($jobId);
$system->jobs()->cancel($data);

$system->merchants()->create($data);
$system->merchants()->getList($query);
$system->merchants()->get($mchno);
$system->merchants()->update($data);
$system->merchants()->updateStatus($data);
$system->merchants()->updateBalance($data);
$system->merchants()->delete($data);
$system->merchants()->ledger($query);
$system->merchants()->ledgerDetail($ledgerId);

$system->sourceSystems()->create($data);
$system->sourceSystems()->getList($query);
$system->sourceSystems()->get($mchno, $sourceSystemCode);
$system->sourceSystems()->update($data);
$system->sourceSystems()->updateStatus($data);
$system->sourceSystems()->delete($data);

$system->planPolicies()->getList($query);
$system->planPolicies()->get($planCode);

$system->platforms()->getList($query);
$system->platforms()->get($platformCode);

$system->platformAccounts()->create($data);
$system->platformAccounts()->getList($query);
$system->platformAccounts()->get($accountCode);
$system->platformAccounts()->update($data);
$system->platformAccounts()->updateBalance($data);
$system->platformAccounts()->updateStatus($data);
$system->platformAccounts()->delete($data);

$system->models()->create($data);
$system->models()->getList($query);
$system->models()->get($modelId);
$system->models()->update($data);
$system->models()->updateStatus($data);
$system->models()->delete($data);
$system->models()->check($data);
$system->models()->checkBatch($data);

$system->agents()->create($data);
$system->agents()->getList($query);
$system->agents()->get($agentId);
$system->agents()->update($data);
$system->agents()->updateStatus($data);
$system->agents()->delete($data);
$system->agents()->check($data);
$system->agents()->checkBatch($data);

$system->ops()->queueStatus();
$system->ops()->workers();
$system->ops()->providerHealth($query);
$system->ops()->failedJobs($query);
$system->ops()->trace($query);
```

## 2. 当前服务端已存在的接口

### Public

- `GET /healthz`
- `GET /api/v1/sse`

说明：

- `GET /api/v1/sse` 为流式订阅接口
- SDK 当前阶段通过 `session($id)->sseSign()` + `client->resolveUrl()` 覆盖其接入需求
- merchant 侧不单独提供 PHP 流式消费封装
- system `messages/send` 的 `response_mode=stream` 由 SDK 解析为事件列表结果

### Merchant

- `POST /admin/merchant/v1/sessions/create`
- `GET /admin/merchant/v1/sessions/detail`
- `POST /admin/merchant/v1/sessions/close`
- `POST /admin/merchant/v1/sessions/sse-sign`
- `POST /admin/merchant/v1/sessions/switch-model`
- `POST /admin/merchant/v1/messages/send`
- `GET /admin/merchant/v1/messages`
- `GET /admin/merchant/v1/messages/detail`
- `GET /admin/merchant/v1/jobs`
- `GET /admin/merchant/v1/jobs/detail`
- `POST /admin/merchant/v1/jobs/cancel`
- `GET /admin/merchant/v1/stats/session`
- `GET /admin/merchant/v1/stats/merchant`

### System

- `POST /admin/system/v1/ai/sessions/create`
- `GET /admin/system/v1/ai/sessions/detail`
- `POST /admin/system/v1/ai/sessions/close`
- `POST /admin/system/v1/ai/sessions/sse-sign`
- `POST /admin/system/v1/ai/sessions/switch-model`
- `POST /admin/system/v1/ai/messages/send`
- `GET /admin/system/v1/ai/messages`
- `GET /admin/system/v1/ai/messages/detail`
- `GET /admin/system/v1/ai/jobs`
- `GET /admin/system/v1/ai/jobs/detail`
- `POST /admin/system/v1/ai/jobs/cancel`
- `GET /admin/system/v1/ops/queue-status`
- `GET /admin/system/v1/ops/workers`
- `GET /admin/system/v1/ops/provider-health`
- `GET /admin/system/v1/ops/failed-jobs`
- `GET /admin/system/v1/ops/trace`
- `POST /admin/system/v1/merchants/create`
- `GET /admin/system/v1/merchants`
- `GET /admin/system/v1/merchants/detail`
- `POST /admin/system/v1/merchants/update`
- `POST /admin/system/v1/merchants/status`
- `POST /admin/system/v1/merchants/balance`
- `POST /admin/system/v1/merchants/delete`
- `GET /admin/system/v1/merchants/ledger`
- `GET /admin/system/v1/merchants/ledger/detail`
- `POST /admin/system/v1/source-systems/create`
- `GET /admin/system/v1/source-systems`
- `GET /admin/system/v1/source-systems/detail`
- `POST /admin/system/v1/source-systems/update`
- `POST /admin/system/v1/source-systems/status`
- `POST /admin/system/v1/source-systems/delete`
- `GET /admin/system/v1/plan-policies`
- `GET /admin/system/v1/plan-policies/detail`
- `POST /admin/system/v1/plan-policies/create`
- `POST /admin/system/v1/plan-policies/update`
- `POST /admin/system/v1/plan-policies/status`
- `POST /admin/system/v1/plan-policies/delete`
- `GET /admin/system/v1/platforms`
- `GET /admin/system/v1/platforms/detail`
- `POST /admin/system/v1/platform-accounts/create`
- `GET /admin/system/v1/platform-accounts`
- `GET /admin/system/v1/platform-accounts/detail`
- `POST /admin/system/v1/platform-accounts/update`
- `POST /admin/system/v1/platform-accounts/balance`
- `POST /admin/system/v1/platform-accounts/status`
- `POST /admin/system/v1/platform-accounts/delete`
- `POST /admin/system/v1/models/create`
- `POST /admin/system/v1/models/check`
- `POST /admin/system/v1/models/check-batch`
- `GET /admin/system/v1/models`
- `GET /admin/system/v1/models/detail`
- `POST /admin/system/v1/models/update`
- `POST /admin/system/v1/models/status`
- `POST /admin/system/v1/models/delete`
- `POST /admin/system/v1/agents/create`
- `POST /admin/system/v1/agents/check`
- `POST /admin/system/v1/agents/check-batch`
- `GET /admin/system/v1/agents`
- `GET /admin/system/v1/agents/detail`
- `POST /admin/system/v1/agents/update`
- `POST /admin/system/v1/agents/status`
- `POST /admin/system/v1/agents/delete`

## 3. SDK 层级映射

### 已有服务端接口，直接映射

- `sessions()->create()`
- `session($id)->get()`
- `session($id)->close()`
- `session($id)->sseSign()`
- `session($id)->switchModel()`
- `session($id)->messages()->getList()`
- `session($id)->messages()->get()`
- `session($id)->messages()->send()`
- `messages()->getList()`
- `messages()->get()`
- `messages()->send()`
- `jobs()->getList()`
- `jobs()->get()`
- `jobs()->cancel()`
- `stats()->session()`
- `stats()->merchant()`
- `session($id)->jobs()->getList()`
- `session($id)->jobs()->get()`
- `session($id)->jobs()->cancel()`
- `session($id)->stats()`
- `ops()->queueStatus()`
- `ops()->workers()`
- `ops()->providerHealth()`
- `ops()->failedJobs()`
- `ops()->trace()`

### SDK 便利方法，服务端暂未直接提供

- `sessions()->getList()`

说明：

- 当前服务端没有 merchant/system 会话列表接口
- 该方法可以先保留在设计层，不应在第一版中声称已实现

## 4. 第一阶段建议实现顺序

1. 配置对象
2. Dispatcher / HeaderBuilder / MerchantSigner
3. `merchant()` / `system()` 入口
4. `MerchantClient` / `SystemClient`
5. `sessions()->create()`
6. `session($id)->get()`
7. `session($id)->messages()->getList()`
8. `session($id)->messages()->get()`
9. `session($id)->messages()->send()`
10. jobs / stats / merchants / models 等外围资源
