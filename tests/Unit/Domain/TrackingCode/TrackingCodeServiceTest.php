<?php
namespace GrShareCode\Tests\Unit\Domain\TrackingCode;

use GrShareCode\GetresponseApiClient;
use GrShareCode\TrackingCode\TrackingCode;
use GrShareCode\TrackingCode\TrackingCodeService;
use PHPUnit\Framework\TestCase;

class TrackingCodeServiceTest extends TestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;

    public function setUp()
    {
        $this->grApiClientMock = $this->getMockBuilder(GetresponseApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldReturnTrackingCode()
    {
        $this->grApiClientMock
            ->expects($this->once())
            ->method('getAccountFeatures')
            ->willReturn(['feature_tracking' => true]);

       $this->grApiClientMock
            ->expects($this->once())
            ->method('getTrackingCodeSnippet')
            ->willReturn('tracking code snippet - long string');

        $trackingCode = new TrackingCode(true, 'tracking code snippet - long string');

        $trackingCodeService = new TrackingCodeService($this->grApiClientMock);
        $this->assertEquals($trackingCode, $trackingCodeService->getTrackingCode());
    }

}