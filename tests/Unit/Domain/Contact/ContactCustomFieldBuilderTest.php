<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\ContactCustomField\ContactCustomField;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldBuilder;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldsCollection;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class ContactCustomFieldBuilderTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
class ContactCustomFieldBuilderTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnMergedCustomFieldCollection()
    {
        $contactCustomFieldsCollection = $this->getContactCustomFieldCollectionFromArray([
            ['id' => 'grId1', 'value' => ['grValue1']],
            ['id' => 'grId2', 'value' => ['grValue2']],
            ['id' => 'grId3', 'value' => ['grValue3']],
            ['id' => 'grId6', 'value' => ['grValue6_a', 'grValue6_b', 'grValue6_c']],
            ['id' => 'grId7', 'value' => ['grValue7_c', 'grValue7_d']],
            ['id' => 'grId8', 'value' => ['grValue8']]
        ]);

        $newContactCustomFieldsCollection = $this->getContactCustomFieldCollectionFromArray([
            ['id' => 'grId4', 'value' => ['grValue4']],
            ['id' => 'grId2', 'value' => ['grValueNewValueId']],
            ['id' => 'grId5', 'value' => ['grValue5']],
            ['id' => 'grId6', 'value' => ['grValue6_c', 'grValue6_d']],
            ['id' => 'grId7', 'value' => ['grValue7_a', 'grValue7_b', 'grValue7_c']],
            ['id' => 'grId9', 'value' => ['grValue9']]
        ]);

        $customFieldMerger = new ContactCustomFieldBuilder(
            $contactCustomFieldsCollection,
            $newContactCustomFieldsCollection
        );

        $expectedCustomFieldCollection = new ContactCustomFieldsCollection();
        $expectedCustomFieldCollection->add(new ContactCustomField('grId1', ['grValue1']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId2', ['grValue2', 'grValueNewValueId']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId3', ['grValue3']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId6', ['grValue6_a', 'grValue6_b', 'grValue6_c', 4 => 'grValue6_d']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId7', ['grValue7_c', 'grValue7_d', 'grValue7_a', 'grValue7_b']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId8', ['grValue8']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId4', ['grValue4']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId5', ['grValue5']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId9', ['grValue9']));

        $this->assertEquals($expectedCustomFieldCollection, $customFieldMerger->getMergedCustomFieldsCollection());
    }

    /**
     * @param array $customFields
     * @return ContactCustomFieldsCollection
     */
    private function getContactCustomFieldCollectionFromArray(array $customFields)
    {
        $contactCustomFieldsCollection = new ContactCustomFieldsCollection();

        foreach ($customFields as $customField) {
            $contactCustomFieldsCollection->add(
                new ContactCustomField($customField['id'], $customField['value'])
            );
        }

        return $contactCustomFieldsCollection;
    }

}
