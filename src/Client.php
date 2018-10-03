<?php

namespace ChinaDivisions;

use ChinaDivisions\Exceptions\ResponseException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Spatie\ArrayToXml\ArrayToXml;
use function GuzzleHttp\Psr7\build_query;

class Client
{
    protected $gateway = 'https://shidclink.cainiao.com/gateway/link.do';

    /**
     * 签名器
     *
     * @var Signature
     */
    protected $signer;

    /**
     * 请求器
     *
     * @var ClientInterface
     */
    protected $httpClient;

    public function __construct(Signature $signer = null, ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new HttpClient();
        $this->signer = $signer ?? new Signature();
    }

    /**
     * 构建请求
     *
     * @param string       $msgType
     * @param string       $logisticProviderId
     * @param string|array $logisticsInterface
     * @param string       $salt
     *
     * @return RequestInterface
     */
    public function build($msgType, $logisticProviderId, $logisticsInterface, $salt)
    {
        if (is_array($logisticsInterface)) {
            $logisticsInterface = ArrayToXml::convert($logisticsInterface, 'request', true);
        }

        $dataDigest = $this->signer->make($logisticsInterface, $salt);

        $body = build_query([
            'msg_type'             => $msgType,
            'logistic_provider_id' => $logisticProviderId,
            'logistics_interface'  => $logisticsInterface,
            'data_digest'          => $dataDigest,
        ]);

        return new Request('POST', $this->gateway, ['Content-Type' => 'application/x-www-form-urlencoded'], $body);
    }

    public function send(RequestInterface $request)
    {
        $response = $this->httpClient->send($request);

        $content = $response->getBody()->getContents();
        if ($content == '') {
            throw new BadResponseException('Response content is empty.', $request, $response);
        }

        $result = json_decode($content, true);
        if ($result === null) {
            throw new BadResponseException('Bad response format.', $request, $response);
        }

        $success = $result['success'];
        if ($success != 'true') {
            throw new ResponseException($result['errorMsg'], intval($result['errorCode']));
        }

        return $result;
    }
}
