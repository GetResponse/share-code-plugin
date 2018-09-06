<?php
namespace GrShareCode\Tests\Unit\Domain\Api;

use GrShareCode\Api\ApiType;
use GrShareCode\Api\ApiTypeException;
use GrShareCode\Api\Authorization;
use GrShareCode\Api\OauthAuthorization;
use GrShareCode\Api\UserAgentHeader;
use GrShareCode\GetresponseApi;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiTypeTest
 * @package GrShareCode\Tests\Unit\Domain\Cart
 */
class ApiTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider GetInvalidApiTypeProvider
     */
    public function shouldThrowExceptionWhenInvalidApiType($type)
    {
        $this->expectException(ApiTypeException::class);
    }

    /**
     * @test
     * @dataProvider apiUrlProvider
     * @param string $type
     * @param null|string $domain
     * @param string $url
     * @throws ApiTypeException
     */
    public function shouldReturnValidApiUrl($type, $domain, $url)
    {
        $apiType = new OauthAuthorization('', '', $type, $domain);
        self::assertEquals($url, $apiType->getApiUrl());
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
     * @dataProvider validApiTypeProvider
     * @doesNotPerformAssertions
     */
    public function shouldValidateApiType($type)
    {
        $userAgentHeader = new UserAgentHeader('shopify', '3.9', '234');
        new GetresponseApi( $type, 'x app id', $userAgentHeader);
    }

    /**
     * @return array
     * @throws ApiTypeException
     */
    public function validApiTypeProvider()
    {
        return [
            [new OauthAuthorization('', '', '', Authorization::SMB)],
            [new OauthAuthorization('', '', '', Authorization::MX_US)],
            [new OauthAuthorization('', '', '', Authorization::MX_PL)],
        ];
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
}
