<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Config;

/**
 * 商户端配置对象。
 *
 * 负责保存商户鉴权所需的商户号、来源系统、访问密钥和超时配置。
 *
 * 更新时间：2026-04-11
 *
 * @method static self fromArray(array $config) 从数组构建商户端配置对象
 * @method bool isValid() 判断当前配置是否合法
 * @method string errorCode() 获取配置错误码
 * @method string errorMessage() 获取配置错误信息
 */
final class MerchantConfig
{
    public string $baseUri;
    public string $mchNo;
    public string $sourceSystem;
    public string $accessSecret;
    public float $timeout;
    public string $requestIdPrefix;
    private bool $valid;
    private string $errorCode;
    private string $errorMessage;

    /**
     * 初始化商户端配置对象。
     *
     * @param string $baseUri 服务基础地址
     * @param string $mchNo 商户号
     * @param string $sourceSystem 来源系统编码
     * @param string $accessSecret 商户访问密钥
     * @param float $timeout 请求超时时间
     * @param string $requestIdPrefix 请求 ID 前缀
     */
    public function __construct(
        string $baseUri,
        string $mchNo,
        string $sourceSystem,
        string $accessSecret,
        float $timeout = 10.0,
        string $requestIdPrefix = 'req'
    ) {
        $baseUri = rtrim(trim($baseUri), '/');
        $mchNo = trim($mchNo);
        $sourceSystem = trim($sourceSystem);
        $accessSecret = trim($accessSecret);

        $this->baseUri = $baseUri;
        $this->mchNo = $mchNo;
        $this->sourceSystem = $sourceSystem;
        $this->accessSecret = $accessSecret;
        $this->timeout = $timeout;
        $this->requestIdPrefix = $requestIdPrefix;
        $this->valid = true;
        $this->errorCode = '';
        $this->errorMessage = '';

        if ($baseUri === '') {
            $this->invalidate('INVALID_CONFIG', 'MerchantConfig.baseUri 不能为空');
            return;
        }
        if ($mchNo === '') {
            $this->invalidate('INVALID_CONFIG', 'MerchantConfig.mchNo 不能为空');
            return;
        }
        if ($sourceSystem === '') {
            $this->invalidate('INVALID_CONFIG', 'MerchantConfig.sourceSystem 不能为空');
            return;
        }
        if ($accessSecret === '') {
            $this->invalidate('INVALID_CONFIG', 'MerchantConfig.accessSecret 不能为空');
        }
    }

    /**
     * 从数组中构建商户端配置对象。
     *
     * @param array $config 原始配置数组
     * @return self 商户端配置对象
     */
    public static function fromArray(array $config): self
    {
        return new self(
            (string) ($config['base_uri'] ?? ''),
            (string) ($config['mch_no'] ?? ''),
            (string) ($config['source_system'] ?? 'default'),
            (string) ($config['access_secret'] ?? ''),
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
