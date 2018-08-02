<?php
namespace GrShareCode\Tests\Unit\Domain\CustomField;

use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\CustomField\CustomField;
use GrShareCode\CustomField\CustomFieldCollection;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\GetresponseApi;
use PHPUnit\Framework\TestCase;

/**
 * Class CustomFieldServiceTest
 * @package GrShareCode\Tests\Unit\Domain\CustomField
 */
class CustomFieldServiceTest extends TestCase
{
    /** @var GetresponseApi|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiMock;

    public function setUp()
    {
        $this->getResponseApiMock = $this->getMockBuilder(GetresponseApi::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldReturnAllCustomFields()
    {
        $this->getResponseApiMock
            ->expects($this->exactly(3))
            ->method('getCustomFields')
            ->willReturnOnConsecutiveCalls(
                [['name' => 'customFieldName1', 'customFieldId' => 'grCustomFieldId1']],
                [['name' => 'customFieldName2', 'customFieldId' => 'grCustomFieldId2']],
                [['name' => 'customFieldName3', 'customFieldId' => 'grCustomFieldId3']]
            );

        $this->getResponseApiMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn(['TotalPages' => '3']);


        $customFieldCollection = new CustomFieldCollection();
        $customFieldCollection->add(new CustomField('grCustomFieldId1', 'customFieldName1'));
        $customFieldCollection->add(new CustomField('grCustomFieldId2', 'customFieldName2'));
        $customFieldCollection->add(new CustomField('grCustomFieldId3', 'customFieldName3'));

        $contactService = new CustomFieldService($this->getResponseApiMock);
        $this->assertEquals($customFieldCollection, $contactService->getAllCustomFields());
    }
}