# 更新记录

## 待发布

## 0.1.3

- 新增 `merchant` / `system` 会话列表接口对接
- 补充会话列表相关 SDK 接口文档
- 补充会话列表相关单元测试

## 0.1.2

- 移除函数式入口 `functions.php`
- 新增 `TaohuaHub\Ai2\Factory` 统一静态入口
- 同步调整入口文档与测试用例

## 0.1.1

- 新增 `system->ops()` 运维资源接口
- 修正会话上下文资源强制绑定 `session_id`
- 支持解析 `system ai/messages/send` 流式响应事件
- 补充鉴权、路由映射、SSE 解析与传输层测试
- 补充 Packagist 安装说明与 SDK 使用文档

## 0.1.0

- 初始化 PHP SDK 基础骨架
- 确定 `TaohuaHub\\Ai2` 命名空间
- 建立 `client / resource / context` 分层结构
- 补充调度、鉴权、签名等基础能力占位实现
- 补充首版接口规划文档
