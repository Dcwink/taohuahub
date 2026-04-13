<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Exception;

use RuntimeException;

/**
 * SDK 异常基类。
 *
 * 所有 SDK 自定义异常都应继承此类，方便调用方统一捕获。
 *
 * 更新时间：2026-04-11
 */
class Ai2Exception extends RuntimeException
{
}
