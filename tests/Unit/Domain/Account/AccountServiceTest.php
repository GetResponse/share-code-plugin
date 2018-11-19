<?php

namespace GrShareCode\Tests\Unit\Domain\Account;

use GrShareCode\Account\AccountService;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class AccountServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Account
 */
class AccountServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;
    /** @var AccountService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->sut = new AccountService($this->getResponseApiClientMock);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetAccount()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getAccountInfo')
            ->willReturn([
                'firstName' => 'firstname',
                'lastName' => 'lastname',
                'email' => 'email@example.com',
                'companyName' => 'company',
                'phone' => '+12 123 123 123',
                'city' => 'City',
                'street' => 'Street',
                'zipCode' => '12-123'
            ]);

        $account = $this->sut->getAccount();

        self::assertEquals('firstname', $account->getFirstName());
        self::assertEquals('lastname', $account->getLastName());
        self::assertEquals('email@example.com', $account->getEmail());
        self::assertEquals('company', $account->getCompanyName());
        self::assertEquals('+12 123 123 123', $account->getPhone());
        self::assertEquals('City', $account->getCity());
        self::assertEquals('Street', $account->getStreet());
        self::assertEquals('12-123', $account->getZipCode());

        self::assertEquals('firstname lastname', $account->getFullName());
        self::assertEquals('City Street 12-123', $account->getFullAddress());

    }

    /**
     * @test
     */
    public function checkConnectionShouldReturnFalseOnFail()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('checkConnection')
            ->willThrowException(new GetresponseApiException());

        self::assertFalse($this->sut->isConnectionAvailable());
    }

    /**
     * @test
     */
    public function checkConnectionShouldReturnTrueOnSuccess()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('checkConnection')
            ->willReturn([]);

        self::assertTrue($this->sut->isConnectionAvailable());
    }

}