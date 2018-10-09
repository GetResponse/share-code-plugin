<?php
namespace GrShareCode;

use GrShareCode\Api\ApiKeyAuthorization;
use GrShareCode\Api\Authorization;
use GrShareCode\Api\OauthAuthorization;
use GrShareCode\Api\UserAgentHeader;

/**
 * Class GetresponseApi
 * @package ShareCode
 */
class GetresponseApi
{
    const TIMEOUT = 8;

    /** @var string */
    private $xAppId;

    /** @var OauthAuthorization|ApiKeyAuthorization */
    private $authorization;

    /** @var array */
    private $headers;

    /** @var UserAgentHeader */
    private $userAgentHeader;

    /** @var array */
    private $unauthorizedResponseCodes = [1014, 1018, 1017];

    /**
     * @param Authorization $authorization
     * @param string $xAppId
     * @param UserAgentHeader $userAgentHeader
     */
    public function __construct(Authorization $authorization, $xAppId, UserAgentHeader $userAgentHeader)
    {
        $this->authorization = $authorization;
        $this->xAppId = $xAppId;
        $this->userAgentHeader = $userAgentHeader;
    }

    /**
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function checkConnection()
    {
        try {
            $account = $this->sendRequest('accounts');

            if (!isset($account['accountId'])) {
                throw GetresponseApiException::createForInvalidAuthentication();
            }
        } catch (AccountNotExistsException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw GetresponseApiException::createForInvalidAuthentication();
        }
    }

    /**
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
     */
    public function getContactByEmail($email, $listId)
    {
        $params = [
            'query' => [
                'email' => $email,
                'campaignId' => $listId,
            ],
        ];

        $result = $this->sendRequest('contacts?' . $this->setParams($params));

        return is_array($result) ? reset($result) : [];
    }

    /**
     * @param string $email
     * @param string $listId
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getContactWithCustomFieldsByEmail($email, $listId)
    {
        $params = [
            'query' => [
                'email' => $email,
                'campaignId' => $listId
            ],
            'additionalFlags' => 'forceCustoms'
        ];

        $result = $this->sendRequest('contacts?' . $this->setParams($params));

        return is_array($result) ? reset($result) : [];
    }

    /**
     * @param string $contactId
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getContactById($contactId)
    {
        $result = $this->sendRequest('contacts/' . $contactId);

        return is_array($result) ? $result : [];
    }

    /**
     * @param $params
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function createContact($params)
    {
        return $this->sendRequest('contacts', 'POST', $params);
    }

    /**
     * @param string $email
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function searchContacts($email)
    {
        $params = ['query' => ['email' => $email]];
        return $this->sendRequest('contacts?'.$this->setParams($params));
    }

    /**
     * @param $contactId
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function deleteContact($contactId)
    {
        $this->sendRequest('contacts/' . $contactId, 'DELETE');
    }

    /**
     * @param int $contactId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
     */
    public function getCustomFields($page, $perPage)
    {
        return $this->sendRequest('custom-fields?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function createCustomField($params)
    {
        return $this->sendRequest('custom-fields', 'POST', $params);
    }

    /**
     * @param string $customFieldId
     * @return string
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function deleteCustomField($customFieldId)
    {
        return $this->sendRequest('custom-fields/' . $customFieldId, 'DELETE');
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getWebForms($page, $perPage)
    {
        return $this->sendRequest('webforms?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param string $lang
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getSubscriptionConfirmationSubject($lang = 'EN')
    {
        return $this->sendRequest('subscription-confirmations/subject/'  . $lang, 'GET', [], true);
    }

    /**
     * @param string $lang
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getSubscriptionConfirmationBody($lang = 'EN')
    {
        return $this->sendRequest('subscription-confirmations/body/'  . $lang, 'GET', [], true);
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getFromFields($page, $perPage)
    {
        return $this->sendRequest('from-fields?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
     */
    public function getContactList($page, $perPage)
    {
        return $this->sendRequest('campaigns?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param string $id
     * @return array
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function getContactListById($id)
    {
        return $this->sendRequest('campaigns/' . $id);
    }


    /**
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function createContactList(array $params)
    {
        return $this->sendRequest('campaigns', 'POST', $params);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getAutoresponders($page, $perPage)
    {
        return $this->sendRequest('autoresponders?' . $this->setParams(['page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param string $campaignId
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getCampaignAutoresponders($campaignId, $page, $perPage)
    {
        return $this->sendRequest('autoresponders?' . $this->setParams(['query' => ['campaignId' => $campaignId], 'page' => $page, 'perPage' => $perPage]), 'GET', [], true);
    }

    /**
     * @param int $id
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getAutoresponderById($id)
    {
        return $this->sendRequest('autoresponders/' . $id, 'GET', [], true);
    }

    /**
     * @param array $params
     * @return string
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function createShop($params)
    {
        $shop = $this->sendRequest('shops', 'POST', $params);
        return !empty($shop['shopId']) ? $shop['shopId'] : '';
    }

    /**
     * @param string $shopId
     * @return string
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function deleteShop($shopId)
    {
        return $this->sendRequest('shops/' . $shopId, 'DELETE');
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
     */
    public function createProductVariant($shopId, $productId, $params)
    {
        return $this->sendRequest('shops/'.$shopId.'/products/'.$productId.'/variants', 'POST', $params);
    }

    /**
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getAccountFeatures()
    {
        return (array)$this->sendRequest('accounts/features');
    }

    /**
     * @return string
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
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
     * @throws AccountNotExistsException
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
        $apiMethod = $this->authorization->getApiUrl() . $apiMethod;

        $headers = [
            'Content-Type: application/json',
            'User-Agent: ' . $this->userAgentHeader->getUserAgentInfo(),
            'X-APP-ID: ' . $this->xAppId,
        ];

        $headers[] = $this->authorization->getAuthorizationHeader();

        // for GetResponse 360
        if ($this->authorization->isMx()) {
            $headers[] = 'X-Domain: ' . $this->authorization->getDomain();
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

            if (isset($response['code']) && in_array($response['code'], $this->unauthorizedResponseCodes)) {
                throw new AccountNotExistsException($response['message'], $response['code']);
            }

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

    /**
     * @return OauthAuthorization|ApiKeyAuthorization
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }
}
