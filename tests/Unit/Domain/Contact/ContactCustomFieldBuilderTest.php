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
            ['id' => 'grId1', 'value' => ['grValue1', 'grValue2']],
            ['id' => 'grId2', 'value' => ['grValue2', 'grValue3']],
            ['id' => 'grId3', 'value' => ['grValue3']]
        ]);

        $newContactCustomFieldsCollection = $this->getContactCustomFieldCollectionFromArray([
            ['id' => 'grId4', 'value' => ['grValue4']],
            ['id' => 'grId2', 'value' => ['grValueNewValueId', 'grValueNewValueId2']],
            ['id' => 'grId5', 'value' => ['grValue5']]
        ]);

        $customFieldMerger = new ContactCustomFieldBuilder(
            $contactCustomFieldsCollection,
            $newContactCustomFieldsCollection
        );

        $expectedCustomFieldCollection = new ContactCustomFieldsCollection();
        $expectedCustomFieldCollection->add(new ContactCustomField('grId1', ['grValue1', 'grValue2']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId2', ['grValueNewValueId', 'grValueNewValueId2']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId3', ['grValue3']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId4', ['grValue4']));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId5', ['grValue5']));

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
