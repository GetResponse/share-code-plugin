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

    /**
     * @param GetresponseApi $grApi
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApi $grApi, DbRepositoryInterface $dbRepository)
    {
        $this->grApi = $grApi;
        $this->dbRepository = $dbRepository;
    }

    /**
     * @throws GetresponseApiException
     */
    public function checkConnection()
    {
        try {
            $this->grApi->checkConnection();
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return array
     * @throws GetresponseApiException
     */
    public function getAccountInfo()
    {
        try {
            $response = $this->grApi->getAccountInfo();
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $email
     * @param string $listId
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactByEmail($email, $listId)
    {
        try {
            $response = $this->grApi->getContactByEmail($email, $listId);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $contactId
     * @return array
     * @throws GetresponseApiException
     */
    public function getContactById($contactId)
    {
        try {
            $response = $this->grApi->getContactById($contactId);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createContact($params)
    {
        try {
            $response = $this->grApi->createContact($params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $email
     * @return array
     * @throws GetresponseApiException
     */
    public function searchContacts($email)
    {
        try {
            $response = $this->grApi->searchContacts($email);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param $contactId
     * @throws GetresponseApiException
     */
    public function deleteContact($contactId)
    {
        try {
            $this->grApi->deleteContact($contactId);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param int $contactId
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function updateContact($contactId, $params)
    {
        try {
            $response = $this->grApi->updateContact($contactId, $params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $shopId
     * @param array $product
     * @return array
     * @throws GetresponseApiException
     */
    public function createProduct($shopId, $product)
    {
        try {
            $response = $this->grApi->createProduct($shopId, $product);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->updateProduct($shopId, $productId, $params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $shopId
     * @param array $params
     * @return string
     * @throws GetresponseApiException
     */
    public function createCart($shopId, $params)
    {
        try {
            $response = $this->grApi->createCart($shopId, $params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->updateCart($shopId, $cartId, $params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->createOrder($shopId, $params, $skipAutomation);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->updateOrder($shopId, $orderId, $params, $skipAutomation);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }

    }
    /**
     * @param string $shopId
     * @param string $cartId
     * @return array
     * @throws GetresponseApiException
     */
    public function removeCart($shopId, $cartId)
    {
        try {
            $response = $this->grApi->removeCart($shopId, $cartId);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->getCustomFields($page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createCustomField($params)
    {
        try {
            $response = $this->grApi->createCustomField($params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $customFieldId
     * @return string
     * @throws GetresponseApiException
     */
    public function deleteCustomField($customFieldId)
    {
        try {
            $response = $this->grApi->deleteCustomField($customFieldId);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->getWebForms($page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $lang
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationSubject($lang = 'EN')
    {
        try {
            $response = $this->grApi->getSubscriptionConfirmationSubject($lang);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $lang
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationBody($lang = 'EN')
    {
        try {
            $response = $this->grApi->getSubscriptionConfirmationBody($lang);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->getFromFields($page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->getForms($page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param $name
     *
     * @return mixed|null
     * @throws GetresponseApiException
     */
    public function getCustomFieldByName($name)
    {
        try {
            $response = $this->grApi->getCustomFieldByName($name);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->getContactList($page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @param array $params
     * @return array
     * @throws GetresponseApiException
     */
    public function createContactList(array $params)
    {
        try {
            $response = $this->grApi->createContactList($params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws GetresponseApiException
     */
    public function getAutoresponders($page, $perPage)
    {
        try {
            $response = $this->grApi->getAutoresponders($page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->getCampaignAutoresponders($campaignId, $page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param int $id
     * @return array|mixed
     * @throws GetresponseApiException
     */
    public function getAutoresponderById($id)
    {
        try {
            $response = $this->grApi->getAutoresponderById($id);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $params
     * @return string
     * @throws GetresponseApiException
     */
    public function createShop($params)
    {
        try {
            $response = $this->grApi->createShop($params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $shopId
     * @return string
     * @throws GetresponseApiException
     */
    public function deleteShop($shopId)
    {
        try {
            $response = $this->grApi->deleteShop($shopId);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->getShops($page, $perPage);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $response = $this->grApi->createProductVariant($shopId, $productId, $params);
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return array
     * @throws GetresponseApiException
     */
    public function getAccountFeatures()
    {
        try {
            $response = $this->grApi->getAccountFeatures();
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return string
     * @throws GetresponseApiException
     */
    public function getTrackingCodeSnippet()
    {
        try {
            $response = $this->grApi->getTrackingCodeSnippet();
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
            return $response;
        } catch (AccountNotExistsException $e) {
            $this->dbRepository->markAccountAsInvalid($this->grApi->getApiKey());
            $this->removeAccountIfRequired();
            throw new GetresponseApiException($e->getMessage(), $e->getCode(), $e);
        }
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
        $firstOccurrence = $this->dbRepository->getInvalidAccountFirstOccurrenceDate($this->grApi->getApiKey());

        if (!empty($firstOccurrence) && (new DateTime('now'))->diff((new DateTime($firstOccurrence)))->days > 1) {
            $this->dbRepository->disconnectAccount($this->grApi->getApiKey());
            $this->dbRepository->markAccountAsValid($this->grApi->getApiKey());
        }
    }
}
