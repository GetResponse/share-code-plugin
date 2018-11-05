<?php

namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class ContactTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
class ContactTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToCheckIfHasOriginCustomField()
    {
        $contactCustomFieldsCollection = new ContactCustomFieldsCollection();

        $contactCustomFieldsCollection->add(
            new ContactCustomField('cid1', ['c1value1', 'c1value2'])
        );

        $contactCustomFieldsCollection->add(
            new ContactCustomField('cid2', ['c2value1'])
        );

        $contact = new Contact(
            'id',
            'name',
            'example@example.com',
            $contactCustomFieldsCollection
        );

        self::assertFalse($contact->hasOriginCustomField(new ContactCustomField('cid1', ['c1value1'])));
        self::assertFalse($contact->hasOriginCustomField(new ContactCustomField('cid1', ['c1value1', 'c1value2'])));
        self::assertTrue($contact->hasOriginCustomField(new ContactCustomField('cid2', ['c2value1'])));
    }
}