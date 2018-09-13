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
     * @dataProvider incorrectAuthorizationParams
     * @param $accessToken
     * @param $refreshToken
     * @param $type
     * @param $domain
     * @throws ApiTypeException
     */
    public function shouldThrowExceptionWhenIncorrectParam($accessToken, $refreshToken, $type, $domain)
    {
        $this->expectException(ApiTypeException::class);

        new OauthAuthorization($accessToken, $refreshToken, $type, $domain);
    }

    /**
     * @return array
     */
    public function incorrectAuthorizationParams()
    {
        return [
            ['', 'refreshToken', Authorization::SMB, 'domain.com'],
            ['accessToken', '', Authorization::SMB, 'domain.com'],
            ['accessToken', 'refreshToken', '', 'domain.com'],
            ['accessToken', 'refreshToken', Authorization::MX_US, '']
        ];
    }

    /**
     * @test
     */
    public static function shouldCreateValidAuthorizationInstance()
    {
        $accessToken = 'TokEn';
        $refreshToken = 'RefREsh';
        $domain = 'example.com';
        $type = Authorization::MX_US;

        $auth = new OauthAuthorization($accessToken, $refreshToken, $type, $domain);

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

        $auth = new OauthAuthorization($accessToken, 'RefREsh', Authorization::MX_US,  'example.com');

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
        $authorization = new OauthAuthorization('xyz', 'rsd', $type, 'domain.com');
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
        $apiType = new OauthAuthorization('accessToken', 'refreshToken', $type, $domain);
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
     * @param $isMx
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
            [false, new OauthAuthorization('accessToken', 'refreshToken', Authorization::SMB, 'example.com')],
            [true, new OauthAuthorization('accessToken', 'refreshToken', Authorization::MX_US, 'example.com')],
            [true, new OauthAuthorization('accessToken', 'refreshToken', Authorization::MX_PL, 'example.com')]
        ];
    }
}
