<?php
namespace GrShareCode\Tests\Unit\Domain\CustomField;

use GrShareCode\CustomField\CustomField;
use GrShareCode\CustomField\CustomFieldCollection;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\GetresponseApiClient;
use PHPUnit\Framework\TestCase;

/**
 * Class CustomFieldServiceTest
 * @package GrShareCode\Tests\Unit\Domain\CustomField
 */
class CustomFieldServiceTest extends TestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockBuilder(GetresponseApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldReturnAllCustomFields()
    {
        $this->getResponseApiClientMock
            ->expects($this->exactly(3))
            ->method('getCustomFields')
            ->willReturnOnConsecutiveCalls(
                [['name' => 'customFieldName1', 'customFieldId' => 'grCustomFieldId1']],
                [['name' => 'customFieldName2', 'customFieldId' => 'grCustomFieldId2']],
                [['name' => 'customFieldName3', 'customFieldId' => 'grCustomFieldId3']]
            );

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn(['TotalPages' => '3']);


        $customFieldCollection = new CustomFieldCollection();
        $customFieldCollection->add(new CustomField('grCustomFieldId1', 'customFieldName1'));
        $customFieldCollection->add(new CustomField('grCustomFieldId2', 'customFieldName2'));
        $customFieldCollection->add(new CustomField('grCustomFieldId3', 'customFieldName3'));

        $contactService = new CustomFieldService($this->getResponseApiClientMock);
        $this->assertEquals($customFieldCollection, $contactService->getAllCustomFields());
    }
}