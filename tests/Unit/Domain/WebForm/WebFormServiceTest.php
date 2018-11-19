<?php
namespace GrShareCode\Tests\Unit\Domain\WebForm;

use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\WebForm\Command\GetWebFormCommand;
use GrShareCode\WebForm\WebForm;
use GrShareCode\WebForm\WebFormCollection;
use GrShareCode\WebForm\WebFormService;
use GrShareCode\Tests\Unit\BaseTestCase;

class WebFormServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;
    /** @var WebFormService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->sut = new WebFormService($this->getResponseApiClientMock);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldReturnWebFormCollection()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getWebForms')
            ->willReturn([
                [
                    'webformId' => 'WebFormId_1',
                    'name' => 'webFormName_1',
                    'scriptUrl' => 'scriptUrl_1',
                    'campaign' => ['name' => 'campaignName_1'],
                    'status' => 'enabled'
                ],
                [
                    'webformId' => 'WebFormId_2',
                    'name' => 'webFormName_2',
                    'scriptUrl' => 'scriptUrl_2',
                    'campaign' => ['name' => 'campaignName_2'],
                    'status' => 'disabled'
                ]
            ]);

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getForms')
            ->willReturn([
                [
                    'webformId' => 'WebFormId_3',
                    'name' => 'webFormName_3',
                    'scriptUrl' => 'scriptUrl_3',
                    'campaign' => ['name' => 'campaignName_3'],
                    'status' => 'published'
                ],
                [
                    'webformId' => 'WebFormId_4',
                    'name' => 'webFormName_4',
                    'scriptUrl' => 'scriptUrl_4',
                    'campaign' => ['name' => 'campaignName_4'],
                    'status' => 'disabled'
                ]
            ]);

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
        $webFormCollection->add(new WebForm(
                'WebFormId_3',
                'webFormName_3',
                'scriptUrl_3',
                'campaignName_3',
                'enabled',
                WebForm::VERSION_V2
            )
        );
        $webFormCollection->add(
            new WebForm(
                'WebFormId_4',
                'webFormName_4',
                'scriptUrl_4',
                'campaignName_4',
                'disabled',
                WebForm::VERSION_V2
            )
        );

        $this->assertEquals($webFormCollection, $this->sut->getAllWebForms());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetOldWebFormById()
    {
        $getWebFormCommand = new GetWebFormCommand('id', WebForm::VERSION_V1);

        $this->getResponseApiClientMock
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

        $form = $this->sut->getWebFormById($getWebFormCommand);

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

        $this->getResponseApiClientMock
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

        $form = $this->sut->getWebFormById($getWebFormCommand);

        self::assertEquals('id', $form->getWebFormId());
        self::assertEquals(WebForm::VERSION_V2, $form->getVersion());
    }


}
