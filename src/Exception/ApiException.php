<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Exception;

/**
 * 业务响应异常。
 *
 * 当服务端返回 `ret != 1` 时，统一抛出该异常，并保留原始返回信息。
 *
 * 更新时间：2026-04-11
 *
 * @method int getRet() 获取业务 ret 值
 * @method string getMsgText() 获取业务 msg 文本
 * @method string getErrcode() 获取业务错误码
 * @method string getErrmsg() 获取业务错误描述
 * @method array getRaw() 获取原始响应数组
 */
final class ApiException extends Ai2Exception
{
    private int $ret;
    private string $msgText;
    private string $errcode;
    private string $errmsg;
    private array $raw;

    /**
     * 初始化业务响应异常。
     *
     * @param int $ret 业务返回码
     * @param string $msgText 业务消息
     * @param string $errcode 业务错误码
     * @param string $errmsg 业务错误描述
     * @param array $raw 原始响应内容
     */
    public function __construct(
        int $ret,
        string $msgText,
        string $errcode,
        string $errmsg,
        array $raw = []
    ) {
        parent::__construct($errmsg !== '' ? $errmsg : $msgText);
        $this->ret = $ret;
        $this->msgText = $msgText;
        $this->errcode = $errcode;
        $this->errmsg = $errmsg;
        $this->raw = $raw;
    }

    /**
     * 获取业务 ret 值。
     *
     * @return int 业务 ret 值
     */
    public function getRet(): int
    {
        return $this->ret;
    }

    /**
     * 获取业务 msg 文本。
     *
     * @return string 业务消息
     */
    public function getMsgText(): string
    {
        return $this->msgText;
    }

    /**
     * 获取业务错误码。
     *
     * @return string 业务错误码
     */
    public function getErrcode(): string
    {
        return $this->errcode;
    }

    /**
     * 获取业务错误描述。
     *
     * @return string 业务错误描述
     */
    public function getErrmsg(): string
    {
        return $this->errmsg;
    }

    /**
     * 获取原始响应数组。
     *
     * @return array 原始响应数据
     */
    public function getRaw(): array
    {
        return $this->raw;
    }
}
