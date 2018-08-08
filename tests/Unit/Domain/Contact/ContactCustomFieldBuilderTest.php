<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldBuilder;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use PHPUnit\Framework\TestCase;

class ContactCustomFieldBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnMergedCustomFieldCollection()
    {
        $contactCustomFieldsCollection = $this->getCustomFieldCollectionFromArray([
            ['id' => 'grId1', 'value' => 'grValue1'],
            ['id' => 'grId2', 'value' => 'grValue2'],
            ['id' => 'grId3', 'value' => 'grValue3']
        ]);

        $newContactCustomFieldsCollection = $this->getCustomFieldCollectionFromArray([
            ['id' => 'grId4', 'value' => 'grValue4'],
            ['id' => 'grId2', 'value' => 'grValueNewValueId'],
            ['id' => 'grId5', 'value' => 'grValue5']
        ]);

        $customFieldMerger = new ContactCustomFieldBuilder(
            $contactCustomFieldsCollection,
            $newContactCustomFieldsCollection
        );

        $expectedCustomFieldCollection = new ContactCustomFieldsCollection();
        $expectedCustomFieldCollection->add(new ContactCustomField('grId1', 'grValue1'));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId2', 'grValueNewValueId'));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId3', 'grValue3'));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId4', 'grValue4'));
        $expectedCustomFieldCollection->add(new ContactCustomField('grId5', 'grValue5'));

        $this->assertEquals($expectedCustomFieldCollection, $customFieldMerger->getMergedCustomFieldsCollection());
    }

    /**
     * @param array $customFields
     * @return ContactCustomFieldsCollection
     */
    private function getCustomFieldCollectionFromArray(array $customFields)
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
