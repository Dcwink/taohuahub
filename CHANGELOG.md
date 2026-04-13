# Changelog

## Unreleased

## 0.1.2

- refactor: replace free functions with `TaohuaHub\Ai2\Factory` static entrypoints

## 0.1.1

- feat: add `system->ops()` resources for queue, workers, provider health, failed jobs and trace endpoints
- fix: ensure session-scoped resources always enforce the bound `session_id`
- feat: parse `system ai/messages/send` stream responses into SDK result events
- test: add PHPUnit coverage for auth headers, route mapping, SSE decoding and Guzzle transport
- docs: add Packagist installation and SDK usage instructions

## 0.1.0

- initialize PHP SDK skeleton
- define public namespace `TaohuaHub\\Ai2`
- define client/resource/context layering
- add internal dispatcher/auth/signature placeholders
- add API planning documents
