<?php

declare(strict_types=1);

namespace TaohuaHub\Ai2\Internal\Http;

use Throwable;
use TaohuaHub\Ai2\Config\MerchantConfig;
use TaohuaHub\Ai2\Config\SystemConfig;
use TaohuaHub\Ai2\Contract\HttpClientInterface;
use TaohuaHub\Ai2\Internal\Auth\HeaderBuilder;
use TaohuaHub\Ai2\Result\Common\ApiResult;

/**
 * SDK 内部统一调度器。
 *
 * 负责请求序列化、请求头构建、HTTP 调用、统一响应解码，以及原始地址拼装。
 *
 * 更新时间：2026-04-11
 *
 * @method ApiResult get(string $path, array $query = []) 发起 GET 请求并解析为结果对象
     * @method ApiResult rawGet(string $path, array $query = []) 发起 GET 请求并返回统一结果对象
 * @method ApiResult post(string $path, array $payload = []) 发起 POST 请求并解析为结果对象
 * @method ApiResult request(string $method, string $path, array $query = [], ?array $payload = null) 发起请求并解析统一响应
 * @method ApiResult rawRequest(string $method, string $path, array $query = [], ?array $payload = null) 发起请求并返回统一结果对象
 * @method string resolveUrl(string $path) 解析相对路径为完整 URL
 */
final class Dispatcher
{
    private MerchantConfig|SystemConfig $config;
    private HttpClientInterface $httpClient;
    private HeaderBuilder $headerBuilder;
    private ResponseDecoder $responseDecoder;

    /**
     * 初始化统一调度器。
     *
     * @param MerchantConfig|SystemConfig $config 客户端配置对象
     * @param HttpClientInterface $httpClient HTTP 客户端实现
     * @param HeaderBuilder|null $headerBuilder 请求头构建器
     * @param ResponseDecoder|null $responseDecoder 响应解码器
     */
    public function __construct(
        MerchantConfig|SystemConfig $config,
        HttpClientInterface $httpClient,
        ?HeaderBuilder $headerBuilder = null,
        ?ResponseDecoder $responseDecoder = null
    ) {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->headerBuilder = $headerBuilder ?? new HeaderBuilder();
        $this->responseDecoder = $responseDecoder ?? new ResponseDecoder();
    }

    /**
     * 发起 GET 请求并解析统一响应。
     *
     * @param string $path 请求路径
     * @param array $query 查询参数
     * @return ApiResult 解析后的结果对象
     */
    public function get(string $path, array $query = []): ApiResult
    {
        return $this->request('GET', $path, $query, null);
    }

    /**
     * 发起 GET 请求并返回统一结果对象。
     *
     * @param string $path 请求路径
     * @param array $query 查询参数
     * @return ApiResult 统一结果对象
     */
    public function rawGet(string $path, array $query = []): ApiResult
    {
        return $this->rawRequest('GET', $path, $query, null);
    }

    /**
     * 发起 POST 请求并解析统一响应。
     *
     * @param string $path 请求路径
     * @param array $payload POST 请求体
     * @return ApiResult 解析后的结果对象
     */
    public function post(string $path, array $payload = []): ApiResult
    {
        return $this->request('POST', $path, [], $payload);
    }

    /**
     * 发起请求并解析统一响应。
     *
     * @param string $method 请求方法
     * @param string $path 请求路径
     * @param array $query 查询参数
     * @param array|null $payload 请求体数组
     * @return ApiResult 解析后的结果对象
     */
    public function request(string $method, string $path, array $query = [], ?array $payload = null): ApiResult
    {
        try {
            if (method_exists($this->config, 'isValid') && !$this->config->isValid()) {
                return ApiResult::failure(
                    '配置错误',
                    method_exists($this->config, 'errorCode') ? $this->config->errorCode() : 'INVALID_CONFIG',
                    method_exists($this->config, 'errorMessage') ? $this->config->errorMessage() : '客户端配置不合法',
                    [],
                    [],
                    0
                );
            }

            $body = $payload === null ? '' : (string) json_encode($payload, JSON_UNESCAPED_UNICODE);
            $headers = $this->buildHeaders($method, $path, $body);
            $response = $this->httpClient->request(
                $method,
                $this->config->baseUri,
                $path,
                $headers,
                $query,
                $body === '' ? null : $body,
                $this->config->timeout
            );

            return $this->responseDecoder->decode($response);
        } catch (Throwable $throwable) {
            return ApiResult::failure(
                '请求失败',
                'SDK_REQUEST_ERROR',
                $throwable->getMessage(),
                [],
                [],
                0
            );
        }
    }

    /**
     * 发起请求并返回统一结果对象。
     *
     * @param string $method 请求方法
     * @param string $path 请求路径
     * @param array $query 查询参数
     * @param array|null $payload 请求体数组
     * @return ApiResult 统一结果对象
     */
    public function rawRequest(string $method, string $path, array $query = [], ?array $payload = null): ApiResult
    {
        try {
            if (method_exists($this->config, 'isValid') && !$this->config->isValid()) {
                return ApiResult::failure(
                    '配置错误',
                    method_exists($this->config, 'errorCode') ? $this->config->errorCode() : 'INVALID_CONFIG',
                    method_exists($this->config, 'errorMessage') ? $this->config->errorMessage() : '客户端配置不合法',
                    [],
                    [],
                    0
                );
            }

            $body = $payload === null ? '' : (string) json_encode($payload, JSON_UNESCAPED_UNICODE);
            $headers = $this->buildHeaders($method, $path, $body);
            $response = $this->httpClient->request(
                $method,
                $this->config->baseUri,
                $path,
                $headers,
                $query,
                $body === '' ? null : $body,
                $this->config->timeout
            );

            return ApiResult::success(is_array($response) ? $response : ['value' => $response], is_array($response) ? $response : []);
        } catch (Throwable $throwable) {
            return ApiResult::failure(
                '请求失败',
                'SDK_REQUEST_ERROR',
                $throwable->getMessage(),
                [],
                [],
                0
            );
        }
    }

    /**
     * 将相对路径解析为完整 URL。
     *
     * @param string $path 相对路径或完整 URL
     * @return string 完整 URL
     */
    public function resolveUrl(string $path): string
    {
        if ($path === '') {
            return $this->config->baseUri;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return $this->config->baseUri . '/' . ltrim($path, '/');
    }

    /**
     * 构建当前请求所需的认证请求头。
     *
     * @param string $method 请求方法
     * @param string $path 请求路径
     * @param string $body 请求体
     * @return array 请求头数组
     */
    private function buildHeaders(string $method, string $path, string $body): array
    {
        if ($this->config instanceof MerchantConfig) {
            return $this->headerBuilder->buildForMerchant($this->config, $method, $path, $body);
        }

        return $this->headerBuilder->buildForSystem($this->config);
    }
}
