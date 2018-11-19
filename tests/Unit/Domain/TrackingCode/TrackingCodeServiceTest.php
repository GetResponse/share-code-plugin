<?php
namespace GrShareCode\Tests\Unit\Domain\TrackingCode;

use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\Tests\Unit\BaseTestCase;
use GrShareCode\TrackingCode\TrackingCodeService;

/**
 * Class TrackingCodeServiceTest
 * @package GrShareCode\Tests\Unit\Domain\TrackingCode
 */
class TrackingCodeServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;
    /** @var TrackingCodeService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->sut = new TrackingCodeService($this->getResponseApiClientMock);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldReturnTrackingCode()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getAccountFeatures')
            ->willReturn(['feature_tracking' => true]);

       $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getTrackingCodeSnippet')
            ->willReturn('tracking code snippet - long string');

        $trackingCode = $this->sut->getTrackingCode();

        self::assertTrue($trackingCode->isFeatureEnabled());
        self::assertEquals('tracking code snippet - long string', $trackingCode->getSnippet());
    }

}