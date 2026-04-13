# Changelog

## Unreleased

- feat: add `system->ops()` resources for queue, workers, provider health, failed jobs and trace endpoints
- fix: ensure session-scoped resources always enforce the bound `session_id`
- feat: parse `system ai/messages/send` stream responses into SDK result events
- test: add PHPUnit coverage for auth headers, route mapping, SSE decoding and Guzzle transport

## 0.1.0

- initialize PHP SDK skeleton
- define public namespace `TaohuaHub\\Ai2`
- define client/resource/context layering
- add internal dispatcher/auth/signature placeholders
- add API planning documents
