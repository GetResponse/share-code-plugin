<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
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
     * @param bool $updateIfAlreadyExists
     */
    public function shouldCreateValidInstance($email, $name, $contactListId, $dayOfCycle, $customFieldsCollection, $updateIfAlreadyExists)
    {
        self::assertInstanceOf(
            AddContactCommand::class,
            new AddContactCommand(
                $email,
                $name,
                $contactListId,
                $dayOfCycle,
                $customFieldsCollection,
                $updateIfAlreadyExists
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
                true,
            ],
            [
                'email' => 'noone@example.com',
                'name' => '',
                'contact_list_id' => 'va1',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                false,
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
                true,
            ],
            [
                'email' => '',
                'name' => 'name',
                'contact_list_id' => 'va1',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                true,
            ],
            [
                'email' => 'nonoe@example.com',
                'name' => 'name',
                'contact_list_id' => '',
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                true,
            ],
            [
                'email' => 'nonoe@example.com',
                'name' => 'name',
                'contact_list_id' => null,
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                true,
            ],
            [
                'email' => 'nonoe@example.com',
                'name' => 'name',
                'contact_list_id' => false,
                'day_of_cycle' => 0,
                new ContactCustomFieldsCollection(),
                true,
            ],
        ];
    }
}
