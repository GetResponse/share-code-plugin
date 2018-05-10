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
}
