<?php
namespace GrShareCode\Account;

use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

/**
 * Class AccountService
 * @package GrShareCode\Account
 */
class AccountService
{
    /** @var GetresponseApiClient */
    private $getresponseApi;

    /**
     * @param GetresponseApi $getresponseApi
     */
    public function __construct(GetresponseApi $getresponseApi)
    {
        $this->getresponseApi = $getresponseApi;
    }

    /**
     * @return Account
     * @throws GetresponseApiException
     */
    public function getAccount()
    {
        $accountInfo = $this->getresponseApi->getAccountInfo();

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
            $this->getresponseApi->checkConnection();
        } catch (GetresponseApiException $e) {
            return false;
        }

        return true;
    }
}