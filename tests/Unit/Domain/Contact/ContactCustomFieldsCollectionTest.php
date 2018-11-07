<?php

namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class ContactCustomFieldsCollectionTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
class ContactCustomFieldsCollectionTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldRemoveItem()
    {
        $contactCustomFieldsCollection = new ContactCustomFieldsCollection();

        $contactCustomField1 = new ContactCustomField('id1', ['value']);
        $contactCustomField2 = new ContactCustomField('id2', ['value']);
        $contactCustomField3 = new ContactCustomField('id3', ['value']);

        $contactCustomFieldsCollection->add($contactCustomField1);
        $contactCustomFieldsCollection->add($contactCustomField2);
        $contactCustomFieldsCollection->add($contactCustomField3);

        self::assertEquals(3, $contactCustomFieldsCollection->count());
        $contactCustomFieldsCollection->remove($contactCustomField2);
        self::assertEquals(2, $contactCustomFieldsCollection->count());

        self::assertTrue($contactCustomFieldsCollection->contains($contactCustomField1));
        self::assertTrue($contactCustomFieldsCollection->contains($contactCustomField3));
        self::assertFalse($contactCustomFieldsCollection->contains($contactCustomField2));
    }

}