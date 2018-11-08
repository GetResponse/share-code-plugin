<?php

namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\ContactCustomField\ContactCustomField;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldsCollection;
use GrShareCode\Contact\ContactPayloadFactory;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class ContactPayloadFactoryTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
class ContactPayloadFactoryTest extends BaseTestCase
{

    /**
     * @test
     */
    public function shouldCreatePayloadFromAddContactCommand()
    {
        $contactCustomFieldCollection = new ContactCustomFieldsCollection();
        $contactCustomFieldCollection->add(
            new ContactCustomField('cid1', ['value1', 'value2'])
        );

        $contactCustomFieldCollection->add(
            new ContactCustomField('cid2', ['value3'])
        );

        $addContactCommand = new AddContactCommand(
            'example@example.com',
            'name',
            'contactListId',
            1,
            $contactCustomFieldCollection
        );


        self::assertEquals(
            [
                'name' => 'name',
                'email' => 'example@example.com',
                'campaign' => [
                    'campaignId' => 'contactListId',
                ],
                'dayOfCycle' => 1,
                'customFieldValues' => [
                    [
                        'customFieldId' => 'cid1',
                        'value' => ['value1', 'value2']
                    ],
                    [
                        'customFieldId' => 'cid2',
                        'value' => ['value3']
                    ]
                ]
            ],
            (new ContactPayloadFactory())->createFromAddContactCommand($addContactCommand)
        );
    }

    /**
     * @test
     */
    public function shouldSkipNameIfEmpty()
    {
        $addContactCommand = new AddContactCommand(
            'example@example.com',
            '',
            'contactListId',
            null,
            new ContactCustomFieldsCollection()
        );

        self::assertEquals(
            [
                'email' => 'example@example.com',
                'campaign' => [
                    'campaignId' => 'contactListId',
                ]
            ],
            (new ContactPayloadFactory())->createFromAddContactCommand($addContactCommand)
        );
    }

    /**
     * @test
     * @dataProvider dayOfCycleDataProvider
     * @param mixed $input
     * @param bool $existsInPayload
     * @param mixed $value
     */
    public function shouldHandleVariousInputOfCycleDay($input, $existsInPayload, $value)
    {
        $addContactCommand = new AddContactCommand(
            'example@example.com',
            '',
            'contactListId',
            $input,
            new ContactCustomFieldsCollection()
        );

        $expectedPayload = [
            'email' => 'example@example.com',
            'campaign' => [
                'campaignId' => 'contactListId',
            ],
        ];

        if ($existsInPayload) {
            $expectedPayload['dayOfCycle'] = $value;
        }

        self::assertEquals(
            $expectedPayload,
            (new ContactPayloadFactory())->createFromAddContactCommand($addContactCommand)
        );
    }

    /**
     * @return array
     */
    public function dayOfCycleDataProvider()
    {
        return [
            [
                'input' => 0,
                'exists' => true,
                'value' => 0
            ],
            [
                'input' => 1,
                'exists' => true,
                'value' => 1
            ],
            [
                'input' => null,
                'exists' => false,
                'value' => ''
            ],
            [
                'input' => '',
                'exists' => false,
                'value' => ''
            ]
        ];
    }

}