<?php
namespace GrShareCode\Tests\Unit\Domain\Api;

use GrShareCode\Api\ApiTypeException;
use GrShareCode\Api\Authorization;
use GrShareCode\Api\ApiKeyAuthorization;
use GrShareCode\Api\UserAgentHeader;
use GrShareCode\GetresponseApi;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiKeyAuthorizationTest
 * @package GrShareCode\Tests\Unit\Domain\Cart
 */
class ApiKeyAuthorizationTest extends TestCase
{
    /**
     * @test
     * @dataProvider GetInvalidApiTypeProvider
     * @param $type
     */
    public function shouldThrowExceptionWhenInvalidApiType($type)
    {
        $this->expectException(ApiTypeException::class);
        $authorization = new ApiKeyAuthorization('xyz', 'domain.com', $type);
    }

    /**
     * @return array
     */
    public function GetInvalidApiTypeProvider()
    {
        return [
            ['abc'],
            [''],
            ['sMb'],
            ['mxpl']
        ];
    }

    /**
     * @test
     * @dataProvider apiUrlProvider
     * @param string $type
     * @param null|string $domain
     * @param string $url
     */
    public function shouldReturnValidApiUrl($type, $domain, $url)
    {
        $apiType = new ApiKeyAuthorization('', $domain, $type);
        self::assertEquals($url, $apiType->getApiUrl());
    }

    /**
     * @return array
     */
    public function apiUrlProvider()
    {
        return [
            [Authorization::SMB, null, Authorization::API_URL_SMB],
            [Authorization::MX_US, 'https://example.com', Authorization::API_URL_MX_US],
            [Authorization::MX_PL, 'https://example.com', Authorization::API_URL_MX_PL],
        ];
    }


    /**
     * @test
     * @dataProvider validApiTypeProvider
     * @param $authorization
     */
    public function shouldValidateApiType($authorization)
    {
        $userAgentHeader = new UserAgentHeader('shopify', '3.9', '234');
        new GetresponseApi($authorization, 'x app id', $userAgentHeader);
    }

    /**
     * @return array
     * @throws ApiTypeException
     */
    public function validApiTypeProvider()
    {
        return [
            [new ApiKeyAuthorization('', '', Authorization::SMB)],
            [new ApiKeyAuthorization('', 'domain.com', Authorization::MX_US)],
            [new ApiKeyAuthorization('', 'domain.pl', Authorization::MX_PL)],
        ];
    }

    /**
     * @test
     * @dataProvider isMxAccountProvider
     * @param bool $isMx
     * @param Authorization $authorization
     */
    public function shouldCheckIfAccountIsMx($isMx, Authorization $authorization)
    {
        self::assertEquals($isMx, $authorization->isMx());
    }

    /**
     * @return array
     * @throws ApiTypeException
     */
    public function isMxAccountProvider()
    {
        return [
            [false, new ApiKeyAuthorization('', 'example.com', Authorization::SMB)],
            [true, new ApiKeyAuthorization('', 'example.com', Authorization::MX_US)],
            [true, new ApiKeyAuthorization('', 'example.com', Authorization::MX_PL)]
        ];
    }
}
