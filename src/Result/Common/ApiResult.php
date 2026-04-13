<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Result\Common;

/**
 * 通用接口返回结果对象。
 *
 * SDK 统一使用该对象表达成功或失败结果，尽量避免在公开调用层直接向外抛出运行时异常。
 *
 * 更新时间：2026-04-11
 *
 * @method static self success(array $data = [], array $raw = [], int $ret = 1, string $msg = 'success') 构造成功结果
 * @method static self failure(string $msg = 'failed', string $errcode = '', string $errmsg = '', array $data = [], array $raw = [], int $ret = 0) 构造失败结果
 * @method bool isSuccess() 判断当前结果是否成功
 * @method bool isFailed() 判断当前结果是否失败
 * @method int ret() 获取业务 ret 值
 * @method string msg() 获取业务 msg 文本
 * @method array data() 获取业务 data 部分
 * @method string errcode() 获取业务错误码
 * @method string errmsg() 获取业务错误描述
 * @method array raw() 获取原始响应数组
 * @method array toArray() 转换为数组结构
 */
final class ApiResult
{
    private bool $success;
    private int $ret;
    private string $msg;
    private array $data;
    private string $errcode;
    private string $errmsg;
    private array $raw;

    /**
     * 初始化通用返回结果对象。
     *
     * @param bool $success 是否成功
     * @param int $ret 业务 ret 值
     * @param string $msg 业务消息
     * @param array $data 业务 data 数据
     * @param string $errcode 业务错误码
     * @param string $errmsg 业务错误描述
     * @param array $raw 原始响应数组
     */
    public function __construct(
        bool $success,
        int $ret,
        string $msg,
        array $data,
        string $errcode,
        string $errmsg,
        array $raw
    ) {
        $this->success = $success;
        $this->ret = $ret;
        $this->msg = $msg;
        $this->data = $data;
        $this->errcode = $errcode;
        $this->errmsg = $errmsg;
        $this->raw = $raw;
    }

    /**
     * 构造成功结果对象。
     *
     * @param array $data 业务 data 数据
     * @param array $raw 原始响应数组
     * @param int $ret 业务 ret 值
     * @param string $msg 业务消息
     * @return self 成功结果对象
     */
    public static function success(array $data = [], array $raw = [], int $ret = 1, string $msg = 'success'): self
    {
        return new self(true, $ret, $msg, $data, '', '', $raw);
    }

    /**
     * 构造失败结果对象。
     *
     * @param string $msg 业务消息
     * @param string $errcode 业务错误码
     * @param string $errmsg 业务错误描述
     * @param array $data 业务 data 数据
     * @param array $raw 原始响应数组
     * @param int $ret 业务 ret 值
     * @return self 失败结果对象
     */
    public static function failure(
        string $msg = 'failed',
        string $errcode = '',
        string $errmsg = '',
        array $data = [],
        array $raw = [],
        int $ret = 0
    ): self {
        return new self(false, $ret, $msg, $data, $errcode, $errmsg, $raw);
    }

    /**
     * 判断当前结果是否成功。
     *
     * @return bool 是否成功
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * 判断当前结果是否失败。
     *
     * @return bool 是否失败
     */
    public function isFailed(): bool
    {
        return !$this->success;
    }

    /**
     * 获取业务 ret 值。
     *
     * @return int 业务 ret 值
     */
    public function ret(): int
    {
        return $this->ret;
    }

    /**
     * 获取业务 msg 文本。
     *
     * @return string 业务消息
     */
    public function msg(): string
    {
        return $this->msg;
    }

    /**
     * 获取业务 data 数据。
     *
     * @return array 业务数据
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * 获取业务错误码。
     *
     * @return string 业务错误码
     */
    public function errcode(): string
    {
        return $this->errcode;
    }

    /**
     * 获取业务错误描述。
     *
     * @return string 业务错误描述
     */
    public function errmsg(): string
    {
        return $this->errmsg;
    }

    /**
     * 获取原始响应数组。
     *
     * @return array 原始响应
     */
    public function raw(): array
    {
        return $this->raw;
    }

    /**
     * 将结果对象转换为数组。
     *
     * @return array 结果数组
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'ret' => $this->ret,
            'msg' => $this->msg,
            'data' => $this->data,
            'errcode' => $this->errcode,
            'errmsg' => $this->errmsg,
            'raw' => $this->raw,
        ];
    }
}
