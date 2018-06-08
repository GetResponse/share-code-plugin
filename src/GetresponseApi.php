<?php
namespace GrShareCode;

use GrShareCode\Api\ApiType;
use GrShareCode\Api\UserAgentHeader;

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

    /** @var ApiType */
    private $apiType;

    /** @var array */
    private $headers;

    /** @var UserAgentHeader */
    private $userAgentHeader;

    /**
     * @param string $apiKey
     * @param ApiType $apiType
     * @param string $xAppId
     * @param UserAgentHeader $userAgentHeader
     */
    public function __construct($apiKey, ApiType $apiType, $xAppId, UserAgentHeader $userAgentHeader)
    {
        $this->apiKey = $apiKey;
        $this->apiType = $apiType;
        $this->xAppId = $xAppId;
        $this->userAgentHeader = $userAgentHeader;
    }

    /**
     * @throws GetresponseApiException
     */
    public function checkConnection()
    {
        try {
            $account = $this->sendRequest('accounts');

            if (!isset($account['accountId'])) {
                throw GetresponseApiException::createForInvalidApiKey();
            }
        } catch (\Exception $e) {
            throw GetresponseApiException::createForInvalidApiKey();
        }
    }

    /**
     * @return array
     * @throws GetresponseApiException
     */
    public function getAccountInfo()
    {
        return $this->sendRequest('accounts');
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

        $result = $this->sendRequest('contacts?'.$this->setParams($params));

        return is_array($result) ? reset($result) : [];
    }

    /**
     * @param $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createContact($params)
    {
        return $this->sendRequest('contacts', 'POST', $params);
    }

    /**
     * @param int $contactId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function updateContact($contactId, $params)
    {
        return $this->sendRequest('contacts/' . $contactId, 'POST', $params);
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
     * @return string
     * @throws GetresponseApiException
     */
    public function createCart($shopId, $params)
    {
        $result = $this->sendRequest('shops/'.$shopId.'/carts', 'POST', $params);

        return is_array($result) ? $result['cartId'] : '';
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
     * @param bool $skipAutomation
     * @return string
     * @throws GetresponseApiException
     */
    public function createOrder($shopId, $params, $skipAutomation = false)
    {
        $url = 'shops/' . $shopId . '/orders';

        if ($skipAutomation) {
            $url .= '?additionalFlags=skipAutomation';
        }

        $result = $this->sendRequest($url, 'POST', $params);

        return is_array($result) ? $result['orderId'] : '';
    }

    /**
     * @param string $shopId
     * @param string $orderId
     * @param array $params
     * @param bool $skipAutomation
     * @return array
     * @throws GetresponseApiException
     */
    public function updateOrder($shopId, $orderId, $params, $skipAutomation = false)
    {
        $url = 'shops/' . $shopId . '/orders/' . $orderId;

        if ($skipAutomation) {
            $url .= '?additionalFlags=skipAutomation';
        }

        return $this->sendRequest($url, 'POST', $params);

    }
    /**
     * @param string $shopId
     * @param string $cartId
     * @return array
     * @throws GetresponseApiException
     */
    public function removeCart($shopId, $cartId)
    {
        return $this->sendRequest('shops/' . $shopId . '/carts/' . $cartId, 'DELETE');
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getCustomFields($page, $perPage)
    {
        return $this->sendRequest('custom-fields?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getWebForms($page, $perPage)
    {
        return $this->sendRequest('webforms?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getForms($page, $perPage)
    {
        return $this->sendRequest('forms?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param $name
     *
     * @return mixed|null
     * @throws GetresponseApiException
     */
    public function getCustomFieldByName($name)
    {
        $result = (array) $this->sendRequest('custom-fields?' . $this->setParams(['query' => ['name' => $name]]));

        foreach ($result as $custom) {
            if ($custom['name'] === $name) {
                return $custom;
            }
        }

        return null;
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     * @throws GetresponseApiException
     */
    public function getCampaigns($page, $perPage)
    {
        return $this->sendRequest('campaigns?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param string $campaignId
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws GetresponseApiException
     */
    public function getAutoresponders($params, $page, $perPage)
    {
        return $this->sendRequest('autoresponders?' . $this->setParams(['query' => $params, 'page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param int $id
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getAutoresponderById($id)
    {
        return $this->sendRequest('autoresponders/' . $id, 'GET', [], true);
    }

    /**
     * @param array $params
     * @return string
     * @throws GetresponseApiException
     */
    public function createShop($params)
    {
        $shop = $this->sendRequest('contacts', 'POST', $params);
        return !empty($shop['shopId']) ? $shop['shopId'] : '';
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     * @throws GetresponseApiException
     */
    public function getShops($page, $perPage)
    {
        return $this->sendRequest('shops?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
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
     * @return array
     * @throws GetresponseApiException
     */
    public function getAccountFeatures()
    {
        return (array)$this->sendRequest('accounts/features');
    }

    /**
     * @return string
     * @throws GetresponseApiException
     */
    public function getTrackingCodeSnippet()
    {
        $trackingCode = $this->sendRequest('tracking');
        $trackingCode = is_array($trackingCode) ? reset($trackingCode) : [];

        return isset($trackingCode['snippet']) ? $trackingCode['snippet'] : '';
    }

    /**
     * @param string $apiMethod
     * @param string $method
     * @param array $params
     * @param bool $withHeaders
     * @return array|mixed
     * @throws GetresponseApiException
     */
    private function sendRequest($apiMethod, $method = 'GET', $params = [], $withHeaders = false)
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
            'X-Auth-Token: api-key ' . $this->apiKey,
            'Content-Type: application/json',
            'User-Agent: ' . $this->userAgentHeader->getUserAgentInfo(),
            'X-APP-ID: ' . $this->xAppId,
        ];

        // for GetResponse 360
        if ($this->apiType->isMx()) {
            $headers[] = 'X-Domain: ' . $this->apiType->getDomain();
        }

        //also as get method
        $options = [
            CURLOPT_URL            => $apiMethod,
            CURLOPT_ENCODING       => 'gzip,deflate',
            CURLOPT_FRESH_CONNECT  => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_HEADER         => $withHeaders,
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
        $response = curl_exec($curl);

        if (false === $response) {
            $error_message = curl_error($curl);
            curl_close($curl);
            throw GetresponseApiException::createForInvalidCurlResponse($error_message);
        }

        if ($withHeaders) {
            list($headers, $response) = explode("\r\n\r\n", $response, 2);
            $this->headers = $this->prepareHeaders($headers);
        }

        $response = json_decode($response, true);

        curl_close($curl);
        if (isset($response['httpStatus']) && 400 <= $response['httpStatus']) {
            throw GetresponseApiException::createForInvalidApiResponseCode(
                $response['message'],
                $response['httpStatus']
            );
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

    /**
     * @param string $headers
     * @return array
     */
    private function prepareHeaders( $headers ) {
        $headers = explode("\n", $headers);
        foreach ($headers as $header) {
            $params = explode(':', $header, 2);
            $key = isset($params[0]) ? $params[0] : null;
            $value = isset($params[1]) ? $params[1] : null;
            $headers[trim($key)] = trim($value);
        }
        return $headers;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
