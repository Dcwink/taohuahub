# System Agent 接口

类：`TaohuaHub\Ai2\Resource\System\Agents`

## 方法

### `create(array $payload): ApiResult`

- Endpoint: `POST /admin/system/v1/agents/create`
- 主要参数：
  - `account_code`
  - `agent_code`
  - `agent_name`
  - `agent_desc`
  - `status`
  - `input_unit_price`
  - `output_unit_price`
  - `ext_config_code`
  - `check_connectivity`

### `check(array $payload): ApiResult`

- Endpoint: `POST /admin/system/v1/agents/check`
- 主要参数：`agent_id`

### `checkBatch(array $payload): ApiResult`

- Endpoint: `POST /admin/system/v1/agents/check-batch`
- 主要参数：`agent_ids`

### `getList(array $query = []): ApiResult`

- Endpoint: `GET /admin/system/v1/agents`
- 常用查询参数：
  - `page`
  - `pagesize`
  - `s_status`
  - `s_account_code`
  - `s_keyword`

### `get(int $agentId): ApiResult`

- Endpoint: `GET /admin/system/v1/agents/detail`
- 查询参数：`agent_id`

### `update(array $payload): ApiResult`

- Endpoint: `POST /admin/system/v1/agents/update`

### `updateStatus(array $payload): ApiResult`

- Endpoint: `POST /admin/system/v1/agents/status`

### `delete(array $payload): ApiResult`

- Endpoint: `POST /admin/system/v1/agents/delete`
