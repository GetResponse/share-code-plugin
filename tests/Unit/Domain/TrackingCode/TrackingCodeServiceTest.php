<?php
namespace GrShareCode\Tests\Unit\Domain\TrackingCode;

use GrShareCode\GetresponseApi;
use GrShareCode\TrackingCode\TrackingCode;
use GrShareCode\TrackingCode\TrackingCodeService;
use PHPUnit\Framework\TestCase;

class TrackingCodeServiceTest extends TestCase
{
    /** @var GetresponseApi|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiMock;

    public function setUp()
    {
        $this->grApiMock = $this->getMockBuilder(GetresponseApi::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldReturnTrackingCode()
    {
        $this->grApiMock
            ->expects($this->once())
            ->method('getAccountFeatures')
            ->willReturn(['feature_tracking' => true]);

       $this->grApiMock
            ->expects($this->once())
            ->method('getTrackingCodeSnippet')
            ->willReturn('tracking code snippet - long string');

        $trackingCode = new TrackingCode(true, 'tracking code snippet - long string');

        $trackingCodeService = new TrackingCodeService($this->grApiMock);
        $this->assertEquals($trackingCode, $trackingCodeService->getTrackingCode());
    }

}