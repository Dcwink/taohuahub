<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TaohuaHub\Ai2\Internal\Http\ResponseDecoder;
use TaohuaHub\Ai2\Internal\Http\ResponseEnvelope;

final class ResponseDecoderTest extends TestCase
{
    public function testDecodeJsonSuccessResponse(): void
    {
        $result = (new ResponseDecoder())->decode([
            'ret' => 1,
            'msg' => 'success',
            'data' => ['session_id' => '20260413S001'],
        ]);

        self::assertTrue($result->isSuccess());
        self::assertSame('20260413S001', $result->data()['session_id']);
    }

    public function testDecodeJsonFailureResponse(): void
    {
        $result = (new ResponseDecoder())->decode([
            'ret' => 0,
            'msg' => '参数错误',
            'data' => [
                'errcode' => 'INVALID_PARAMS',
                'errmsg' => '缺少 s_session_id 参数',
            ],
        ]);

        self::assertTrue($result->isFailed());
        self::assertSame('INVALID_PARAMS', $result->errcode());
        self::assertSame('缺少 s_session_id 参数', $result->errmsg());
    }

    public function testDecodeStreamDoneEventAsSuccess(): void
    {
        $body = <<<SSE
event: ack
data: {"session_id":"20260413S001","job_id":"20260413J001","response_mode":"stream"}

event: done
data: {"session_id":"20260413S001","job_id":"20260413J001","status":"success","content":"hello"}

SSE;

        $result = (new ResponseDecoder())->decode(
            ResponseEnvelope::wrap(200, 'text/event-stream', $body, ['Content-Type' => 'text/event-stream'])
        );

        self::assertTrue($result->isSuccess());
        self::assertSame('stream', $result->data()['response_mode']);
        self::assertCount(2, $result->data()['events']);
        self::assertSame('done', $result->data()['terminal_event']['event']);
        self::assertSame('20260413J001', $result->data()['terminal_event']['data']['job_id']);
    }

    public function testDecodeStreamErrorEventAsFailure(): void
    {
        $body = <<<SSE
event: ack
data: {"session_id":"20260413S001","job_id":"20260413J001","response_mode":"stream"}

event: error
data: {"session_id":"20260413S001","job_id":"20260413J001","error_code":"SYSTEM_ERROR","error_message":"任务执行失败"}

SSE;

        $result = (new ResponseDecoder())->decode(
            ResponseEnvelope::wrap(200, 'text/event-stream', $body, ['Content-Type' => 'text/event-stream'])
        );

        self::assertTrue($result->isFailed());
        self::assertSame('SYSTEM_ERROR', $result->errcode());
        self::assertSame('任务执行失败', $result->errmsg());
        self::assertSame('error', $result->data()['terminal_event']['event']);
    }
}
