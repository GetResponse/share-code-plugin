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
     */
    public function shouldReturnValidApiUrl($type, $url)
    {
        $apiType = new ApiType($type);
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
            [ApiType::createForMxPl()],
            [ApiType::createForMxUs()],
            [ApiType::createForSMB()]
        ];
    }

    public function ApiUrlProvider()
    {
        return [
            [ApiType::SMB, ApiType::API_URL_SMB],
            [ApiType::MX_US, ApiType::API_URL_MX_US],
            [ApiType::MX_PL, ApiType::API_URL_MX_PL],
        ];
    }
}
