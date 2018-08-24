<?php
namespace GrShareCode\Tests\Unit\Domain\WebForm;

use GrShareCode\GetresponseApiClient;
use GrShareCode\WebForm\WebForm;
use GrShareCode\WebForm\WebFormCollection;
use GrShareCode\WebForm\WebFormService;
use PHPUnit\Framework\TestCase;

class WebFormServiceTest extends TestCase
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
    public function shouldReturnWebFormCollection()
    {
        $this->grApiClientMock
            ->expects($this->exactly(3))
            ->method('getWebForms')
            ->withConsecutive([1, 100], [2, 100], [3, 100])
            ->willReturnOnConsecutiveCalls(
                [
                    [
                        'webformId' => 'WebFormId_1',
                        'name' => 'webFormName_1',
                        'scriptUrl' => 'scriptUrl_1',
                        'campaign' => ['name' => 'campaignName_1'],
                        'status' => 'enabled'
                    ]
                ],
                [
                    [
                        'webformId' => 'WebFormId_2',
                        'name' => 'webFormName_2',
                        'scriptUrl' => 'scriptUrl_2',
                        'campaign' => ['name' => 'campaignName_2'],
                        'status' => 'disabled'
                    ]
                ],
                [
                    [
                        'webformId' => 'WebFormId_3',
                        'name' => 'webFormName_3',
                        'scriptUrl' => 'scriptUrl_3',
                        'campaign' => ['name' => 'campaignName_3'],
                        'status' => 'enabled'
                    ]
                ]
            );

        $this->grApiClientMock
            ->expects($this->exactly(3))
            ->method('getForms')
            ->withConsecutive([1, 100], [2, 100], [3, 100])
            ->willReturnOnConsecutiveCalls(
                [
                    [
                        'webformId' => 'WebFormId_4',
                        'name' => 'webFormName_4',
                        'scriptUrl' => 'scriptUrl_4',
                        'campaign' => ['name' => 'campaignName_4'],
                        'status' => 'published'
                    ]
                ],
                [
                    [
                        'webformId' => 'WebFormId_5',
                        'name' => 'webFormName_5',
                        'scriptUrl' => 'scriptUrl_5',
                        'campaign' => ['name' => 'campaignName_5'],
                        'status' => 'disabled'
                    ]
                ],
                [
                    [
                        'webformId' => 'WebFormId_6',
                        'name' => 'webFormName_6',
                        'scriptUrl' => 'scriptUrl_6',
                        'campaign' => ['name' => 'campaignName_6'],
                        'status' => 'published'
                    ]
                ]
            );

        $this->grApiClientMock
            ->expects($this->exactly(2))
            ->method('getHeaders')
            ->willReturn(['TotalPages' => '3']);

        $webFormCollection = new WebFormCollection();
        $webFormCollection->add(
            new WebForm(
                'WebFormId_1',
                'webFormName_1',
                'scriptUrl_1',
                'campaignName_1',
                'enabled')
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_2',
                'webFormName_2',
                'scriptUrl_2',
                'campaignName_2',
                'disabled'
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_3',
                'webFormName_3',
                'scriptUrl_3',
                'campaignName_3',
                'enabled'
            )
        );
        $webFormCollection->add(new WebForm(
                'WebFormId_4',
                'webFormName_4',
                'scriptUrl_4',
                'campaignName_4',
                'enabled'
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_5',
                'webFormName_5',
                'scriptUrl_5',
                'campaignName_5',
                'disabled'
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_6',
                'webFormName_6',
                'scriptUrl_6',
                'campaignName_6',
                'enabled'
            )
        );

        $shopService = new WebFormService($this->grApiClientMock);
        $this->assertEquals($webFormCollection, $shopService->getAllWebForms());
    }

}
