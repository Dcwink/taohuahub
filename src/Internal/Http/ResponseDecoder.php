<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Internal\Http;

use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * 统一响应解码器。
 *
 * 负责将服务端统一响应格式解析为 `ApiResult`，业务失败时不再直接抛出异常。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult decode(array $payload) 解码统一响应数组
 */
final class ResponseDecoder
{
    /**
     * 解码统一响应数组。
     *
     * @param array $payload 服务端响应数组
     * @return ApiResult 解析后的结果对象
     */
    public function decode(array $payload): ApiResult
    {
        if (ResponseEnvelope::isWrapped($payload)) {
            return $this->decodeWrappedResponse($payload);
        }

        $ret = (int) ($payload['ret'] ?? 0);
        $msg = (string) ($payload['msg'] ?? '');
        $data = $payload['data'] ?? [];

        if (!is_array($data)) {
            $data = ['value' => $data];
        }

        if ($ret !== 1) {
            return ApiResult::failure(
                $msg !== '' ? $msg : 'failed',
                (string) ($data['errcode'] ?? ''),
                (string) ($data['errmsg'] ?? $msg),
                $data,
                $payload,
                $ret
            );
        }

        return ApiResult::success($data, $payload, $ret, $msg !== '' ? $msg : 'success');
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function decodeWrappedResponse(array $payload): ApiResult
    {
        $meta = ResponseEnvelope::meta($payload);
        $contentType = strtolower((string) ($meta['content_type'] ?? ''));
        $body = (string) ($meta['body'] ?? '');
        if ($contentType === 'text/event-stream') {
            return $this->decodeSse($payload, $body);
        }

        return ApiResult::failure(
            '响应格式不支持',
            'UNSUPPORTED_RESPONSE',
            sprintf('当前 SDK 不支持解析 %s 响应', $contentType !== '' ? $contentType : 'unknown'),
            [
                'content_type' => $contentType,
                'body' => $body,
                'headers' => is_array($meta['headers'] ?? null) ? $meta['headers'] : [],
                'status_code' => (int) ($meta['status_code'] ?? 0),
            ],
            $payload,
            0
        );
    }

    /**
     * @param array<string, mixed> $raw
     */
    private function decodeSse(array $raw, string $body): ApiResult
    {
        $events = $this->parseSseEvents($body);
        $terminalEvent = $this->findTerminalEvent($events);
        $data = [
            'response_mode' => 'stream',
            'content_type' => 'text/event-stream',
            'events' => $events,
            'body' => $body,
        ];
        if ($terminalEvent !== null) {
            $data['terminal_event'] = $terminalEvent;
        }

        if (($terminalEvent['event'] ?? '') === 'error') {
            $terminalData = $terminalEvent['data'] ?? [];
            if (!is_array($terminalData)) {
                $terminalData = [];
            }

            return ApiResult::failure(
                (string) ($terminalData['error_message'] ?? 'stream error'),
                (string) ($terminalData['error_code'] ?? 'STREAM_ERROR'),
                (string) ($terminalData['error_message'] ?? 'stream error'),
                $data,
                $raw,
                0
            );
        }

        return ApiResult::success($data, $raw, 1, 'stream completed');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseSseEvents(string $body): array
    {
        $events = [];
        $current = [
            'event' => 'message',
            'data_lines' => [],
            'id' => '',
            'retry' => null,
        ];

        foreach (preg_split("/\r\n|\n|\r/", $body) ?: [] as $line) {
            if ($line === '') {
                $event = $this->finalizeSseEvent($current);
                if ($event !== null) {
                    $events[] = $event;
                }
                $current = [
                    'event' => 'message',
                    'data_lines' => [],
                    'id' => '',
                    'retry' => null,
                ];
                continue;
            }
            if (str_starts_with($line, ':')) {
                continue;
            }

            [$field, $value] = array_pad(explode(':', $line, 2), 2, '');
            $value = ltrim($value, ' ');
            switch ($field) {
                case 'event':
                    $current['event'] = $value !== '' ? $value : 'message';
                    break;
                case 'data':
                    $current['data_lines'][] = $value;
                    break;
                case 'id':
                    $current['id'] = $value;
                    break;
                case 'retry':
                    $current['retry'] = is_numeric($value) ? (int) $value : null;
                    break;
                default:
                    break;
            }
        }

        $event = $this->finalizeSseEvent($current);
        if ($event !== null) {
            $events[] = $event;
        }

        return $events;
    }

    /**
     * @param array<string, mixed> $current
     * @return array<string, mixed>|null
     */
    private function finalizeSseEvent(array $current): ?array
    {
        $rawData = implode("\n", $current['data_lines']);
        if ($rawData === '' && ($current['event'] ?? 'message') === 'message') {
            return null;
        }

        return [
            'event' => (string) ($current['event'] ?? 'message'),
            'id' => (string) ($current['id'] ?? ''),
            'retry' => $current['retry'],
            'data' => $this->decodeSseData($rawData),
            'raw_data' => $rawData,
        ];
    }

    /**
     * @return array<string, mixed>|array{value:mixed}
     */
    private function decodeSseData(string $rawData): array
    {
        if ($rawData === '') {
            return [];
        }

        $decoded = json_decode($rawData, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_array($decoded)) {
                return $decoded;
            }

            return ['value' => $decoded];
        }

        return ['value' => $rawData];
    }

    /**
     * @param array<int, array<string, mixed>> $events
     * @return array<string, mixed>|null
     */
    private function findTerminalEvent(array $events): ?array
    {
        for ($index = count($events) - 1; $index >= 0; $index--) {
            $event = $events[$index];
            if (in_array($event['event'] ?? '', ['done', 'error'], true)) {
                return $event;
            }
        }

        return null;
    }
}
