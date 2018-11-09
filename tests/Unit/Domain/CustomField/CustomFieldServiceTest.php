<?php
namespace GrShareCode\Tests\Unit\Domain\CustomField;

use GrShareCode\CustomField\Command\CreateCustomFieldCommand;
use GrShareCode\CustomField\CustomField;
use GrShareCode\CustomField\CustomFieldCollection;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class CustomFieldServiceTest
 * @package GrShareCode\Tests\Unit\Domain\CustomField
 */
class CustomFieldServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;
    /** @var CustomFieldService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->sut = new CustomFieldService($this->getResponseApiClientMock);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldReturnAllCustomFields()
    {
        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getCustomFields')
            ->willReturn(
                [
                    [
                        'name' => 'customFieldName1',
                        'customFieldId' => 'grCustomFieldId1',
                        'fieldType' => 'customFieldType1',
                        'valueType' => 'customFieldValue1'
                    ],
                    [
                        'name' => 'customFieldName2',
                        'customFieldId' => 'grCustomFieldId2',
                        'fieldType' => 'customFieldType2',
                        'valueType' => 'customFieldValue2'
                    ],
                    [
                        'name' => 'customFieldName3',
                        'customFieldId' => 'grCustomFieldId3',
                        'fieldType' => 'customFieldType3',
                        'valueType' => 'customFieldValue3'
                    ]
                ]
            );

        $customFieldCollection = new CustomFieldCollection();
        $customFieldCollection->add(
            new CustomField(
                'grCustomFieldId1',
                'customFieldName1',
                'customFieldType1',
                'customFieldValue1'
            )
        );

        $customFieldCollection->add(
            new CustomField(
                'grCustomFieldId2',
                'customFieldName2',
                'customFieldType2',
                'customFieldValue2'
            )
        );

        $customFieldCollection->add(
            new CustomField(
                'grCustomFieldId3',
                'customFieldName3',
                'customFieldType3',
                'customFieldValue3'
            )
        );


        $this->assertEquals($customFieldCollection, $this->sut->getAllCustomFields());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldCreateCustomField()
    {
        $createCustomFieldCommand = new CreateCustomFieldCommand('origin', ['originName']);

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('createCustomField')
            ->with(
                [
                    'name' => 'origin',
                    'type' => 'text',
                    'hidden' => false,
                    'values' => ['originName']
                ]
            );

        $this->sut->createCustomField($createCustomFieldCommand);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldDeleteCustomField()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('deleteCustomField')
            ->with('customId');

        $this->sut->deleteCustomFieldById('customId');
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetCustomFieldByName()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getCustomFieldByName')
            ->with('customName');

        $this->sut->getCustomFieldByName('customName');
    }


    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldReturnTextFieldCustomFields()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getCustomFields')
            ->willReturn(
                [
                    [
                        'name' => 'customFieldName1',
                        'customFieldId' => 'grCustomFieldId1',
                        'fieldType' => 'text',
                        'valueType' => 'string'
                    ],
                    [
                        'name' => 'customFieldName2',
                        'customFieldId' => 'grCustomFieldId2',
                        'fieldType' => 'customFieldType2',
                        'valueType' => 'customFieldValue2'
                    ],
                    [
                        'name' => 'customFieldName3',
                        'customFieldId' => 'grCustomFieldId3',
                        'fieldType' => 'text',
                        'valueType' => 'string'
                    ]
                ]
            );

        $customFieldCollection = new CustomFieldCollection();
        $customFieldCollection->add(new CustomField('grCustomFieldId1', 'customFieldName1', 'text', 'string'));
        $customFieldCollection->add(new CustomField('grCustomFieldId3', 'customFieldName3', 'text', 'string'));

        $contactService = new CustomFieldService($this->getResponseApiClientMock);
        $this->assertEquals(
            $customFieldCollection,
            $contactService->getCustomFieldsForMapping()
        );
    }

}