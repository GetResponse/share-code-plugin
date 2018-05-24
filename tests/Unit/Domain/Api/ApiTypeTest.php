<?php
namespace GrShareCode\Tests\Unit\Domain\Api;

use GrShareCode\Api\ApiType;
use GrShareCode\Api\ApiTypeException;
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
        new ApiType($type);
    }

    /**
     * @test
     * @dataProvider ApiUrlProvider
     * @param string $type
     * @param null|string $domain
     * @param string $url
     * @throws ApiTypeException
     */
    public function shouldReturnValidApiUrl($type, $domain, $url)
    {
        $apiType = new ApiType($type, $domain);
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
     * @dataProvider GetValidApiTypeProvider
     * @doesNotPerformAssertions
     */
    public function shouldValidateApiType($type)
    {
        new GetresponseApi('api key', $type, 'x app id');
    }

    /**
     * @return array
     */
    public function GetValidApiTypeProvider()
    {
        return [
            [ApiType::createForMxPl('https://example.com')],
            [ApiType::createForMxUs('https://example.com')],
            [ApiType::createForSMB()]
        ];
    }

    /**
     * @return array
     */
    public function ApiUrlProvider()
    {
        return [
            [ApiType::SMB, null, ApiType::API_URL_SMB],
            [ApiType::MX_US, 'https://example.com', ApiType::API_URL_MX_US],
            [ApiType::MX_PL, 'https://example.com', ApiType::API_URL_MX_PL],
        ];
    }
}
