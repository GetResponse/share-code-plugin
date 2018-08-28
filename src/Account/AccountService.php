<?php
namespace GrShareCode\Account;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

/**
 * Class AccountService
 * @package GrShareCode\Account
 */
class AccountService
{
    /** @var GetresponseApiClient */
    private $getresponseApiClient;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     */
    public function __construct(GetresponseApiClient $getresponseApiClient)
    {
        $this->getresponseApiClient = $getresponseApiClient;
    }

    /**
     * @return Account
     * @throws GetresponseApiException
     */
    public function getAccount()
    {
        $accountInfo = $this->getresponseApiClient->getAccountInfo();

        return new Account(
            $accountInfo['firstName'],
            $accountInfo['lastName'],
            $accountInfo['email'],
            $accountInfo['companyName'],
            $accountInfo['phone'],
            $accountInfo['city'],
            $accountInfo['street'],
            $accountInfo['zipCode']
        );
    }

    /**
     * @return bool
     */
    public function isConnectionAvailable()
    {
        try {
            $this->getresponseApiClient->checkConnection();
        } catch (GetresponseApiException $e) {
            return false;
        }

        return true;
    }
}