<?php
namespace GrShareCode\Tests\Unit\Domain\WebForm;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\WebForm\Command\GetWebFormCommand;
use GrShareCode\WebForm\WebForm;
use GrShareCode\WebForm\WebFormCollection;
use GrShareCode\WebForm\WebFormService;
use GrShareCode\Tests\Unit\BaseTestCase;

class WebFormServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;
    /** @var WebFormService */
    private $webFormService;

    public function setUp()
    {
        $this->grApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->webFormService = new WebFormService($this->grApiClientMock);
    }

    /**
     * @test
     * @throws GetresponseApiException
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
                'enabled',
                WebForm::VERSION_V1
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_2',
                'webFormName_2',
                'scriptUrl_2',
                'campaignName_2',
                'disabled',
                WebForm::VERSION_V1
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_3',
                'webFormName_3',
                'scriptUrl_3',
                'campaignName_3',
                'enabled',
                WebForm::VERSION_V1
            )
        );
        $webFormCollection->add(new WebForm(
                'WebFormId_4',
                'webFormName_4',
                'scriptUrl_4',
                'campaignName_4',
                'enabled',
                WebForm::VERSION_V2
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_5',
                'webFormName_5',
                'scriptUrl_5',
                'campaignName_5',
                'disabled',
                WebForm::VERSION_V2
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_6',
                'webFormName_6',
                'scriptUrl_6',
                'campaignName_6',
                'enabled',
                WebForm::VERSION_V2
            )
        );

        $this->assertEquals($webFormCollection, $this->webFormService->getAllWebForms());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetOldWebFormById()
    {
        $getWebFormCommand = new GetWebFormCommand('id', WebForm::VERSION_V1);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('getWebFormById')
            ->with('id')
            ->willReturn([
                'webformId' => 'id',
                'name' => 'form_name',
                'scriptUrl' => 'https://app.getresponse.com/view_webform.js?u=as11ab&wid=11212315',
                'status' => 'enabled',
                'campaign' => ['name' => 'campaign_name']
            ]);

        $form = $this->webFormService->getWebFormById($getWebFormCommand);

        self::assertEquals('id', $form->getWebFormId());
        self::assertEquals(WebForm::VERSION_V1, $form->getVersion());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetNewWebFormById()
    {
        $getWebFormCommand = new GetWebFormCommand('id', WebForm::VERSION_V2);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('getFormById')
            ->with('id')
            ->willReturn([
                'webformId' => 'id',
                'name' => 'form_name',
                'scriptUrl' => 'https://app.getresponse.com/view_webform.js?u=as11ab&wid=11212315',
                'status' => 'published',
                'campaign' => ['name' => 'campaign_name']
            ]);

        $form = $this->webFormService->getWebFormById($getWebFormCommand);

        self::assertEquals('id', $form->getWebFormId());
        self::assertEquals(WebForm::VERSION_V2, $form->getVersion());
    }


}
