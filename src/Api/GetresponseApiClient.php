<?php
namespace GrShareCode\Api;

use DateTime;
use GrShareCode\Api\Exception\AccountNotExistsException;
use GrShareCode\Api\Exception\CustomFieldNotFoundException;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\DbRepositoryInterface;

/**
 * Class GetresponseApiClient
 * @package GrShareCode\Api
 */
class GetresponseApiClient
{
    /** @var GetresponseApi */
    private $grApi;
    /** @var DbRepositoryInterface */
    private $dbRepository;
    /** @var string */
    private $authorizationKey;
    /** @var array */
    private $shortTermCache;

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
     * @param bool $withCustoms
     * @return array
     * @throws GetresponseApiException
     */
    public function findContactByEmailAndListId($email, $listId, $withCustoms = false)
    {
        return $this->execute(function () use ($email, $listId, $withCustoms) {

            $key = md5($email .$listId .$withCustoms);

            if (isset($this->shortTermCache[$key])) {
                return $this->shortTermCache[$key];
            }

            $response = $this->grApi->getContactByEmailAndListId($email, $listId, $withCustoms);
            $this->shortTermCache[$key] = $response;
            return $response;
        });
    }

    /**
     * @param string $contactId
     * @param bool $withCustoms
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactById($contactId, $withCustoms)
    {
        return $this->execute(function () use ($contactId, $withCustoms) {
            return $this->grApi->getContactById($contactId, $withCustoms);
        });
    }

    /**
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     * @throws CustomFieldNotFoundException
     */
    public function createContact($params)
    {
        try {
            return $this->execute(function () use ($params) {
                return $this->grApi->createContact($params);
            });
        } catch (GetresponseApiException $exception) {

            if (1 === preg_match('#Custom field by id: (?<customId>\w+) not found#', $exception->getMessage(), $matched)) {
                throw CustomFieldNotFoundException::createWithCustomFieldId($matched['customId']);
            } else {
                throw $exception;
            }
        }
    }

    /**
     * @param string $email
     * @param bool $withCustoms
     * @return array
     * @throws GetresponseApiException
     */
    public function searchContacts($email, $withCustoms)
    {
        return $this->execute(function () use ($email, $withCustoms) {
            return $this->grApi->searchContacts($email, $withCustoms);
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
     * @return array
     * @throws GetresponseApiException
     */
    public function updateOrder($shopId, $orderId, $params)
    {
        return $this->execute(function () use ($shopId, $orderId, $params) {
            return $this->grApi->updateOrder($shopId, $orderId, $params);
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
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getCustomFields()
    {
        return $this->execute(function () {
            return $this->grApi->getCustomFields();
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
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getWebForms()
    {
        return $this->execute(function () {
            return $this->grApi->getWebForms();
        });
    }

    /**
     * @param string $id
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getWebFormById($id)
    {
        return $this->execute(function () use ($id) {
            return $this->grApi->getWebFormById($id);
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
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getFromFields()
    {
        return $this->execute(function () {
            return $this->grApi->getFromFields();
        });
    }

    /**
     * @return array
     * @throws GetresponseApiException
     */
    public function getForms()
    {
        return $this->execute(function () {
            return $this->grApi->getForms();
        });
    }

    /**
     * @param string $id
     * @return array
     * @throws GetresponseApiException
     */
    public function getFormById($id)
    {
        return $this->execute(function () use ($id) {
            return $this->grApi->getFormById($id);
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
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactList()
    {
        return $this->execute(function () {
            return $this->grApi->getContactList();
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
     * @param string|null $campaignId
     * @return array
     * @throws GetresponseApiException
     */
    public function getAutoresponders($campaignId = null)
    {
        return $this->execute(function () use ($campaignId) {
            return $this->grApi->getAutoresponders($campaignId);
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
     * @return array
     * @throws GetresponseApiException
     */
    public function getShops()
    {
        return $this->execute(function () {
            return $this->grApi->getShops();
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
