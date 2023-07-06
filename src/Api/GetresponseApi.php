<?php
namespace GrShareCode\Api;

use GrShareCode\Api\Authorization\Authorization;
use GrShareCode\Api\Exception\AccountNotExistsException;
use GrShareCode\Api\Exception\GetresponseApiException;

/**
 * Class GetresponseApi
 * @package GrShareCode\Api
 */
class GetresponseApi
{
    const PAGINATION_PER_PAGE = 100;

    const TIMEOUT = 8;

    /** @var string */
    private $xAppId;

    /** @var Authorization */
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
     * @param bool $withCustoms
     * @return array
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function getContactByEmailAndListId($email, $listId, $withCustoms)
    {
        $params = [
            'query' => [
                'email' => $email,
                'campaignId' => $listId
            ],
        ];

        if ($withCustoms) {
            $params['additionalFlags'] = 'forceCustoms';
        }

        $result = $this->sendRequest('contacts?' . $this->setParams($params));

        return is_array($result) ? reset($result) : [];
    }

    /**
     * @param string $contactId
     * @param bool $withCustoms
     * @return array
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function getContactById($contactId, $withCustoms)
    {
        $params = [];
        if ($withCustoms) {
            $params['additionalFlags'] = 'forceCustoms';
        }

        $result = $this->sendRequest('contacts/' . $contactId . '?' . $this->setParams($params));

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
     * @param bool $withCustoms
     * @return array
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function searchContacts($email, $withCustoms)
    {
        $params = ['query' => ['email' => $email]];
        if ($withCustoms) {
            $params['additionalFlags'] = 'forceCustoms';
        }

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
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function updateOrder($shopId, $orderId, $params)
    {
        return $this->sendRequest('shops/' . $shopId . '/orders/' . $orderId, 'POST', $params);

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
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getCustomFields()
    {
        return $this->fetchDataWithPagination('custom-fields');
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function fetchDataWithPagination($method, $params = [])
    {
        $params['page'] = 1;
        $params['perPage'] = self::PAGINATION_PER_PAGE;

        do {
            $data[] = $this->sendRequest($method . '?' . $this->setParams($params), 'GET', [], true);
            $params['page']++;
        } while ($params['page'] <= $this->getHeaders()['TotalPages']);

        return call_user_func_array('array_merge', $data);
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
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getWebForms()
    {
        return $this->fetchDataWithPagination('webforms');
    }

    /**
     * @param $id
     * @return array|mixed
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function getWebFormById($id)
    {
        return $this->sendRequest('webforms/' . $id);
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
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getFromFields()
    {
        return $this->fetchDataWithPagination('from-fields');
    }

    /**
     * @return array|mixed
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getForms()
    {
        return $this->fetchDataWithPagination('forms');
    }

    /**
     * @param string $id
     * @return array|mixed
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function getFormById($id)
    {
        return $this->sendRequest('forms/' . $id, 'GET');
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
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getContactList()
    {
        return $this->fetchDataWithPagination('campaigns');
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
     * @param null $campaignId
     * @return array
     * @throws AccountNotExistsException
     * @throws GetresponseApiException
     */
    public function getAutoresponders($campaignId = null)
    {
        $params = [];
        if (!empty($campaignId)) {
            $params = ['query' => ['campaignId' => $campaignId]];
        }

        return $this->fetchDataWithPagination('autoresponders', $params);
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
     * @return array
     * @throws GetresponseApiException
     * @throws AccountNotExistsException
     */
    public function getShops()
    {
        return $this->fetchDataWithPagination('shops');
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

            if (count($params) === 2 && !empty($params[0]) && !empty($params[1])) {
                $headers[trim($params[0])] = trim($params[1]);
            }
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
     * @return Authorization
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }
}
