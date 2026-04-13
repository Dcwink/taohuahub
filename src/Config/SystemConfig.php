<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Config;

/**
 * system 端配置对象。
 *
 * 负责保存管理员接口调用所需的系统密钥和请求基础配置。
 *
 * 更新时间：2026-04-11
 *
 * @method static self fromArray(array $config) 从数组构建 system 端配置对象
 * @method bool isValid() 判断当前配置是否合法
 * @method string errorCode() 获取配置错误码
 * @method string errorMessage() 获取配置错误信息
 */
final class SystemConfig
{
    public string $baseUri;
    public string $systemKey;
    public float $timeout;
    public string $requestIdPrefix;
    private bool $valid;
    private string $errorCode;
    private string $errorMessage;

    /**
     * 初始化 system 端配置对象。
     *
     * @param string $baseUri 服务基础地址
     * @param string $systemKey 系统密钥
     * @param float $timeout 请求超时时间
     * @param string $requestIdPrefix 请求 ID 前缀
     */
    public function __construct(
        string $baseUri,
        string $systemKey,
        float $timeout = 10.0,
        string $requestIdPrefix = 'req'
    ) {
        $baseUri = rtrim(trim($baseUri), '/');
        $systemKey = trim($systemKey);

        $this->baseUri = $baseUri;
        $this->systemKey = $systemKey;
        $this->timeout = $timeout;
        $this->requestIdPrefix = $requestIdPrefix;
        $this->valid = true;
        $this->errorCode = '';
        $this->errorMessage = '';

        if ($baseUri === '') {
            $this->invalidate('INVALID_CONFIG', 'SystemConfig.baseUri 不能为空');
            return;
        }
        if ($systemKey === '') {
            $this->invalidate('INVALID_CONFIG', 'SystemConfig.systemKey 不能为空');
        }
    }

    /**
     * 从数组中构建 system 端配置对象。
     *
     * @param array $config 原始配置数组
     * @return self system 端配置对象
     */
    public static function fromArray(array $config): self
    {
        return new self(
            (string) ($config['base_uri'] ?? ''),
            (string) ($config['system_key'] ?? ''),
            (float) ($config['timeout'] ?? 10.0),
            (string) ($config['request_id_prefix'] ?? 'req')
        );
    }

    /**
     * 判断当前配置是否合法。
     *
     * @return bool 是否合法
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * 获取配置错误码。
     *
     * @return string 配置错误码
     */
    public function errorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * 获取配置错误信息。
     *
     * @return string 配置错误信息
     */
    public function errorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * 标记当前配置非法。
     *
     * @param string $errorCode 错误码
     * @param string $errorMessage 错误信息
     * @return void
     */
    private function invalidate(string $errorCode, string $errorMessage): void
    {
        $this->valid = false;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }
}
