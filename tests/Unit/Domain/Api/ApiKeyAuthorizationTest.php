<?php
namespace GrShareCode\Tests\Unit\Domain\Api;

use GrShareCode\Api\ApiTypeException;
use GrShareCode\Api\Authorization;
use GrShareCode\Api\ApiKeyAuthorization;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiKeyAuthorizationTest
 * @package GrShareCode\Tests\Unit\Domain\Cart
 */
class ApiKeyAuthorizationTest extends TestCase
{
    /**
     * @test
     * @dataProvider incorrectAuthorizationParams
     * @param $apiKey
     * @param $type
     * @param $domain
     */
    public function shouldThrowExceptionWhenIncorrectParam($apiKey, $type, $domain)
    {
        $this->expectException(ApiTypeException::class);
        new ApiKeyAuthorization($apiKey, $type, $domain);
    }

    /**
     * @return array
     */
    public function incorrectAuthorizationParams()
    {
        return [
            ['', Authorization::SMB, 'domain.com'],
            ['accessToken', '', 'domain.com'],
            ['accessToken', Authorization::MX_US, '']
        ];
    }

    /**
     * @test
     * @dataProvider GetInvalidApiTypeProvider
     * @param $type
     */
    public function shouldThrowExceptionWhenInvalidApiType($type)
    {
        $this->expectException(ApiTypeException::class);
        new ApiKeyAuthorization('xyz', $type, 'domain.com');
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
        $apiType = new ApiKeyAuthorization('apiKey', $type, $domain);
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
            [false, new ApiKeyAuthorization('apiKey', Authorization::SMB)],
            [true, new ApiKeyAuthorization('apiKey', Authorization::MX_US, 'example.com')],
            [true, new ApiKeyAuthorization('apiKey', Authorization::MX_PL, 'example.com')]
        ];
    }
}
