<?php
namespace GrShareCode;

use GrShareCode\Api\ApiType;

/**
 * Class GetresponseApi
 * @package ShareCode
 */
class GetresponseApi
{
    const TIMEOUT = 8;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $xAppId;

    /** @var string */
    private $domain;

    /** @var ApiType */
    private $apiType;

    /**
     * @param string $apiKey
     * @param ApiType $apiType
     * @param string $xAppId
     */
    public function __construct($apiKey, ApiType $apiType, $xAppId)
    {
        $this->apiKey = $apiKey;
        $this->apiType = $apiType;
        $this->xAppId = $xAppId;
    }

    /**
     * @param string $email
     * @param string $listId
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactByEmail($email, $listId)
    {
        $params = [
            'query' => [
                'email'      => $email,
                'campaignId' => $listId,
            ],
        ];

        return $this->sendRequest('contacts?'.$this->setParams($params));
    }

    /**
     * @param string $shopId
     * @param array $product
     * @return array
     * @throws GetresponseApiException
     */
    public function createProduct($shopId, $product)
    {
        return $this->sendRequest('shops/'.$shopId.'/products', 'POST', $product);
    }

    /**
     * @param string $shopId
     * @param string $productId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function updateProduct($shopId, $productId, $params)
    {
        return $this->sendRequest('shops/'.$shopId.'/products/'.$productId, 'POST', $params);
    }

    /**
     * @param string $shopId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createCart($shopId, $params)
    {
        return $this->sendRequest('shops/'.$shopId.'/carts', 'POST', $params);
    }

    /**
     * @param string $shopId
     * @param string $cartId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function updateCart($shopId, $cartId, $params)
    {
        return $this->sendRequest('shops/'.$shopId.'/carts/'.$cartId, 'POST', $params);
    }

    /**
     * @param string $shopId
     * @param array $params
     * @return string
     */
    public function createOrder($shopId, $params)
    {
        return '';
    }

    /**
     * @param string $shopId
     * @param string $orderId
     * @param array $params
     */
    public function updateOrder($shopId, $orderId, $params)
    {

    }

    /**
     * @param string $shopId
     * @param string $getCartId
     */
    public function removeCart($shopId, $getCartId)
    {

    }

    /**
     * @param string $shopId
     * @param string $productId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createProductVariant($shopId, $productId, $params)
    {
        return $this->sendRequest('shops/'.$shopId.'/products/'.$productId.'/variants', 'POST', $params);

    }

    /**
     * @param string $apiMethod
     * @param string $method
     * @param array $params
     * @return array|mixed
     * @throws GetresponseApiException
     */
    private function sendRequest($apiMethod, $method = 'GET', $params = [])
    {
        if (empty($apiMethod)) {
            return [
                'httpStatus'      => '400',
                'code'            => '1010',
                'codeDescription' => 'Error in external resources',
                'message'         => 'Invalid api method',
            ];
        }

        $json_params = json_encode($params);
        $apiMethod = $this->apiType->getApiUrl().$apiMethod;

        $headers = [
            'X-Auth-Token: api-key '.$this->apiKey,
            'Content-Type: application/json',
            'User-Agent: PHP GetResponse client 0 . 0 . 1',
            'X-APP-ID: '.$this->xAppId,
        ];

        // for GetResponse 360
        if (isset($this->domain)) {
            $headers[] = 'X-Domain: '.$this->domain;
        }

        //also as get method
        $options = [
            CURLOPT_URL            => $apiMethod,
            CURLOPT_ENCODING       => 'gzip,deflate',
            CURLOPT_FRESH_CONNECT  => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => $headers,
        ];

        if ($method == 'POST') {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $json_params;
        } else {
            if ($method == 'DELETE') {
                $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $curlExec = curl_exec($curl);

        if (false === $curlExec) {
            $error_message = curl_error($curl);
            curl_close($curl);
            throw GetresponseApiException::createForInvalidCurlResponse($error_message);
        }

        $response = json_decode($curlExec, true);
        curl_close($curl);
        if (isset($response['httpStatus']) && 400 <= $response['httpStatus']) {
            throw GetresponseApiException::createForInvalidApiResponseCode($response['message'],
                $response['httpStatus']);
        }

        return $response;
    }

    /**
     * @param array $params
     * @return string
     */
    private function setParams($params)
    {
        $result = [];
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $result[$key] = $value;
            }
        }

        return http_build_query($result);
    }
}
