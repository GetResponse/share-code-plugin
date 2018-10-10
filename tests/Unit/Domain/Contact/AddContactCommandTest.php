<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\AddContactCommand;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Tests\Unit\BaseTestCase;
use GrShareCode\Validation\Assert\InvalidArgumentException;

/**
 * Class AddContactCommandTest
 * @package GrShareCode\Contact
 */
class AddContactCommandTest extends BaseTestCase
{

    /**
     * @test
     * @dataProvider validDataProvider
     * @param string $email
     * @param string $name
     * @param string $contactListId
     * @param int $dayOfCycle
     * @param ContactCustomFieldsCollection $customFieldsCollection
     * @param string $originValue
     */
    public function shouldCreateValidInstance($email, $name, $contactListId, $dayOfCycle, $customFieldsCollection, $originValue)
    {
        self::assertInstanceOf(
            AddContactCommand::class,
            new AddContactCommand(
                $email,
                $name,
                $contactListId,
                $dayOfCycle,
                $customFieldsCollection,
                $originValue
            )
        );
    }

    /**
     * @return array
     */
    public function validDataProvider()
    {
        return [
            [
                'email' => 'noone@example.com',
                'name' => 'name',
                'contact_list_id' => 'va1',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                'origin'
            ],
            [
                'email' => 'noone@example.com',
                'name' => '',
                'contact_list_id' => 'va1',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                'origin'
            ],

        ];
    }

    /**
     * @test
     * @dataProvider invalidDataProvider
     * @param string $email
     * @param string $name
     * @param string $contactListId
     * @param int $dayOfCycle
     * @param ContactCustomFieldsCollection $customFieldsCollection
     * @param string $originValue
     */
    public function shouldThrowExceptionWhenInvalidParameters($email, $name, $contactListId, $dayOfCycle, $customFieldsCollection, $originValue)
    {
        self::expectException(InvalidArgumentException::class);

        new AddContactCommand(
            $email,
            $name,
            $contactListId,
            $dayOfCycle,
            $customFieldsCollection,
            $originValue
        );
    }

    public function invalidDataProvider()
    {
        return [
            [
                'email' => null,
                'name' => 'name',
                'contact_list_id' => 'va1',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                'origin'
            ],
            [
                'email' => '',
                'name' => 'name',
                'contact_list_id' => 'va1',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                'origin'
            ],
            [
                'email' => 'nonoe@example.com',
                'name' => 'name',
                'contact_list_id' => '',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                'origin'
            ],
            [
                'email' => 'nonoe@example.com',
                'name' => 'name',
                'contact_list_id' => null,
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                'origin'
            ],
            [
                'email' => 'nonoe@example.com',
                'name' => 'name',
                'contact_list_id' => false,
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                'origin'
            ],
        ];
    }
}
