<?php
namespace GrShareCode\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\ContactCustomField\ContactCustomField;

class ContactPayloadFactory
{
    /**
     * @param AddContactCommand $addContactCommand
     * @return array
     */
    public function createFromAddContactCommand(AddContactCommand $addContactCommand)
    {
        $payload = [
            'name' => $addContactCommand->getName(),
            'email' => $addContactCommand->getEmail(),
            'campaign' => [
                'campaignId' => $addContactCommand->getContactListId(),
            ]
        ];

        if (empty(trim($payload['name']))) {
            unset($payload['name']);
        }

        if (!is_null($addContactCommand->getDayOfCycle()) && '' !== $addContactCommand->getDayOfCycle()) {
            $payload['dayOfCycle'] = $addContactCommand->getDayOfCycle();
        }

        /** @var ContactCustomField $customField */
        foreach ($addContactCommand->getContactCustomFieldsCollection() as $customField) {
            $payload['customFieldValues'][] = [
                'customFieldId' => $customField->getId(),
                'value' => $customField->getValue()
            ];
        }

        return $payload;
    }
}