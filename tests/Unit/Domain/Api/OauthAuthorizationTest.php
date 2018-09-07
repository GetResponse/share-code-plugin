<?php
namespace GrShareCode\Tests\Unit\Domain\Api;

use GrShareCode\Api\ApiTypeException;
use GrShareCode\Api\Authorization;
use GrShareCode\Api\OauthAuthorization;
use GrShareCode\Api\UserAgentHeader;
use GrShareCode\GetresponseApi;
use PHPUnit\Framework\TestCase;

/**
 * Class OauthAuthorizationTest
 * @package GrShareCode\Tests\Unit\Domain\Cart
 */
class OauthAuthorizationTest extends TestCase
{
    /**
     * @test
     */
    public static function shouldCreateValidAuthorizationInstance()
    {
        $accessToken = 'TokEn';
        $refreshToken = 'RefREsh';
        $domain = 'example.com';
        $type = Authorization::MX_US;

        $auth = new OauthAuthorization($accessToken, $refreshToken, $domain, $type);

        self::assertEquals($accessToken, $auth->getAccessToken());
        self::assertEquals($refreshToken, $auth->getRefreshToken());
        self::assertEquals($domain, $auth->getDomain());
        self::assertEquals($type, $auth->getType());
    }

    /**
     * @test
     */
    public static function shouldReturnValidAuthorizationHeader()
    {
        $accessToken = 'TokEn';
        $header = 'Authorization: Bearer ' . $accessToken;

        $auth = new OauthAuthorization($accessToken, 'RefREsh', 'example.com', Authorization::MX_US);

        self::assertEquals($header, $auth->getAuthorizationHeader());
    }

    /**
     * @test
     * @dataProvider GetInvalidApiTypeProvider
     * @param $type
     */
    public function shouldThrowExceptionWhenInvalidApiType($type)
    {
        $this->expectException(ApiTypeException::class);
        $authorization = new OauthAuthorization('xyz', 'rsd', 'domain.com', $type);
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
        $apiType = new OauthAuthorization('', '', $domain, $type);
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
            [new OauthAuthorization('', '', '', Authorization::SMB)],
            [new OauthAuthorization('', '', 'domain.com', Authorization::MX_US)],
            [new OauthAuthorization('', '', 'domain.pl', Authorization::MX_PL)],
        ];
    }

    /**
     * @test
     * @dataProvider isMxAccountProvider
     * @param $authorization
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
            [false, new OauthAuthorization('', '', 'example.com', Authorization::SMB)],
            [true, new OauthAuthorization('', '', 'example.com', Authorization::MX_US)],
            [true, new OauthAuthorization('', '', 'example.com', Authorization::MX_PL)]
        ];
    }
}
