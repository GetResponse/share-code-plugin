<?php

namespace GrShareCode;

use DateTime;

/**
 * Class GetresponseApiClient
 * @package ShareCode
 */
class GetresponseApiClient
{
    /** @var GetresponseApi */
    private $grApi;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /** @var string */
    private $authorizationKey;

    /**
     * @param GetresponseApi $grApi
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApi $grApi, DbRepositoryInterface $dbRepository)
    {
        $this->grApi = $grApi;
        $this->dbRepository = $dbRepository;
        $this->authorizationKey = $this->grApi->getAuthorization()->getAccessToken();
    }

    /**
     * @throws GetresponseApiException
     */
    public function checkConnection()
    {
        $this->execute(function () {
            $this->grApi->checkConnection();
        });
    }

    /**
     * @param callable $action
     * @return array|string|int
     * @throws GetresponseApiException
     */
    private function execute(callable $action)
    {
        try {
            $result = $action();
            $this->dbRepository->markAccountAsValid($this->authorizationKey);
            return $result;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->authorizationKey);
            $this->removeAccountIfRequired();
            throw GetresponseApiException::createFromPreviousException($e);
        }
    }

    /**
     * @return array
     * @throws GetresponseApiException
     */
    public function getAccountInfo()
    {
        return $this->execute(function () {
            return $this->grApi->getAccountInfo();
        });
    }

    /**
     * @param string $email
     * @param string $listId
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactByEmail($email, $listId)
    {
        $this->authorizationKey = $this->grApi->getAuthorization()->getAccessToken();
        return $this->execute(function () use ($email, $listId) {
            return $this->grApi->getContactByEmail($email, $listId);
        });
    }

    /**
     * @param string $email
     * @param string $listId
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactWithCustomFieldsByEmail($email, $listId)
    {
        $this->authorizationKey = $this->grApi->getAuthorization()->getAccessToken();
        return $this->execute(function () use ($email, $listId) {
            return $this->grApi->getContactWithCustomFieldsByEmail($email, $listId);
        });
    }

    /**
     * @param string $contactId
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactById($contactId)
    {
        $this->authorizationKey = $this->grApi->getAuthorization()->getAccessToken();
        return $this->execute(function () use ($contactId) {
            return $this->grApi->getContactById($contactId);
        });
    }

    /**
     * @param $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createContact($params)
    {
        $this->authorizationKey = $this->grApi->getAuthorization()->getAccessToken();
        return $this->execute(function () use ($params) {
            return $this->grApi->createContact($params);
        });
    }

    /**
     * @param string $email
     * @return array
     * @throws GetresponseApiException
     */
    public function searchContacts($email)
    {
        return $this->execute(function () use ($email) {
            return $this->grApi->searchContacts($email);
        });
    }

    /**
     * @param $contactId
     * @throws GetresponseApiException
     */
    public function deleteContact($contactId)
    {
        $this->execute(function () use ($contactId) {
             $this->grApi->deleteContact($contactId);
        });
    }

    /**
     * @param int $contactId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function updateContact($contactId, $params)
    {
        return $this->execute(function () use ($contactId, $params) {
            return $this->grApi->updateContact($contactId, $params);
        });
    }

    /**
     * @param string $shopId
     * @param array $product
     * @return array
     * @throws GetresponseApiException
     */
    public function createProduct($shopId, $product)
    {
        return $this->execute(function () use ($shopId, $product) {
            return $this->grApi->createProduct($shopId, $product);
        });
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
        return $this->execute(function () use ($shopId, $productId, $params) {
            return $this->grApi->updateProduct($shopId, $productId, $params);
        });
    }

    /**
     * @param string $shopId
     * @param array $params
     * @return string
     * @throws GetresponseApiException
     */
    public function createCart($shopId, $params)
    {
        return $this->execute(function () use ($shopId, $params) {
            return $this->grApi->createCart($shopId, $params);
        });
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
        return $this->execute(function () use ($shopId, $cartId, $params) {
            return $this->grApi->updateCart($shopId, $cartId, $params);
        });
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
        return $this->execute(function () use ($shopId, $params, $skipAutomation) {
            return $this->grApi->createOrder($shopId, $params, $skipAutomation);
        });
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
        return $this->execute(function () use ($shopId, $orderId, $params, $skipAutomation) {
            return $this->grApi->updateOrder($shopId, $orderId, $params, $skipAutomation);
        });
    }

    /**
     * @param string $shopId
     * @param string $cartId
     * @return array
     * @throws GetresponseApiException
     */
    public function removeCart($shopId, $cartId)
    {
        return $this->execute(function () use ($shopId, $cartId) {
            return $this->grApi->removeCart($shopId, $cartId);
        });
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
        return $this->execute(function () use ($page, $perPage) {
            return $this->grApi->getCustomFields($page, $perPage);
        });
    }

    /**
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createCustomField($params)
    {
        return $this->execute(function () use ($params) {
            return $this->grApi->createCustomField($params);
        });
    }

    /**
     * @param string $customFieldId
     * @return string
     * @throws GetresponseApiException
     */
    public function deleteCustomField($customFieldId)
    {
        return $this->execute(function () use ($customFieldId) {
            return $this->grApi->deleteCustomField($customFieldId);
        });
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
        return $this->execute(function () use ($page, $perPage) {
            return $this->grApi->getWebForms($page, $perPage);
        });
    }

    /**
     * @param string $lang
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationSubject($lang = 'EN')
    {
        return $this->execute(function () use ($lang) {
            return $this->grApi->getSubscriptionConfirmationSubject($lang);
        });
    }

    /**
     * @param string $lang
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationBody($lang = 'EN')
    {
        return $this->execute(function () use ($lang) {
            return $this->grApi->getSubscriptionConfirmationBody($lang);
        });
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getFromFields($page, $perPage)
    {
        return $this->execute(function () use ($page, $perPage) {
            return $this->grApi->getFromFields($page, $perPage);
        });
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     * @throws GetresponseApiException
     */
    public function getForms($page, $perPage)
    {
        return $this->execute(function () use ($page, $perPage) {
            return $this->grApi->getForms($page, $perPage);
        });
    }

    /**
     * @param $name
     *
     * @return mixed|null
     * @throws GetresponseApiException
     */
    public function getCustomFieldByName($name)
    {
        return $this->execute(function () use ($name) {
            return $this->grApi->getCustomFieldByName($name);
        });
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactList($page, $perPage)
    {
        return $this->execute(function () use ($page, $perPage) {
            return $this->grApi->getContactList($page, $perPage);
        });
    }

    /**
     * @param string $id
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactListById($id)
    {
        return $this->execute(function () use ($id) {
            return $this->grApi->getContactListById($id);
        });
    }


    /**
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createContactList(array $params)
    {
        return $this->execute(function () use ($params) {
            return $this->grApi->createContactList($params);
        });
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws GetresponseApiException
     */
    public function getAutoresponders($page, $perPage)
    {
        return $this->execute(function () use ($page, $perPage) {
            return $this->grApi->getAutoresponders($page, $perPage);
        });
    }

    /**
     * @param string $campaignId
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws GetresponseApiException
     */
    public function getCampaignAutoresponders($campaignId, $page, $perPage)
    {
        return $this->execute(function () use ($campaignId, $page, $perPage) {
            return $this->grApi->getCampaignAutoresponders($campaignId, $page, $perPage);
        });
    }

    /**
     * @param int $id
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getAutoresponderById($id)
    {
        return $this->execute(function () use ($id) {
            return $this->grApi->getAutoresponderById($id);
        });
    }

    /**
     * @param array $params
     * @return string
     * @throws GetresponseApiException
     */
    public function createShop($params)
    {
        return $this->execute(function () use ($params) {
            return $this->grApi->createShop($params);
        });
    }

    /**
     * @param string $shopId
     * @return string
     * @throws GetresponseApiException
     */
    public function deleteShop($shopId)
    {
        return $this->execute(function () use ($shopId) {
            return $this->grApi->deleteShop($shopId);
        });
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
        return $this->execute(function () use ($page, $perPage) {
            return $this->grApi->getShops($page, $perPage);
        });
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
        return $this->execute(function () use ($shopId, $productId, $params) {
            return $this->grApi->createProductVariant($shopId, $productId, $params);
        });
    }

    /**
     * @return array
     * @throws GetresponseApiException
     */
    public function getAccountFeatures()
    {
        return $this->execute(function () {
            return $this->grApi->getAccountFeatures();
        });
    }

    /**
     * @return string
     * @throws GetresponseApiException
     */
    public function getTrackingCodeSnippet()
    {
        return $this->execute(function () {
            return $this->grApi->getTrackingCodeSnippet();
        });
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->grApi->getHeaders();
    }

    private function removeAccountIfRequired()
    {
        $firstOccurrence = $this->dbRepository->getInvalidAccountFirstOccurrenceDate($this->authorizationKey);

        if (!empty($firstOccurrence) && (new DateTime('now'))->diff((new DateTime($firstOccurrence)))->days > 1) {
            $this->dbRepository->disconnectAccount($this->authorizationKey);
            $this->dbRepository->markAccountAsValid($this->authorizationKey);
        }
    }
}
