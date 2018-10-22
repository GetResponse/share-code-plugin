<?php
namespace GrShareCode\Contact;

use GrShareCode\Contact\Command\AddContactCommand;

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

        if (empty($payload['name'])) {
            unset($payload['name']);
        }

        if (!empty($addContactCommand->getDayOfCycle())) {
            $payload['dayOfCycle'] = $addContactCommand->getDayOfCycle();
        }

        /** @var ContactCustomField $customField */
        foreach ($addContactCommand->getCustomFieldsCollection() as $customField) {
            $payload['customFieldValues'][] = [
                'customFieldId' => $customField->getId(),
                'value' => [$customField->getValue()]
            ];
        }

        return $payload;
    }
}