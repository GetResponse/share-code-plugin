<?php
namespace GrShareCode\Contact;

use GrShareCode\Contact\Command\GetContactCommand;
use GrShareCode\GrShareCodeException;

/**
 * Class ContactNotFoundException
 * @package GrShareCode\Cart
 */
class ContactNotFoundException extends GrShareCodeException
{

    /**
     * @param GetContactCommand $getContactCommand
     * @return ContactNotFoundException
     */
    public static function createFromGetContactCommand(GetContactCommand $getContactCommand)
    {
        if (null !== $getContactCommand->getId()) {
            return new self(
                sprintf(
                    'Contact with id: %s not found',
                    $getContactCommand->getId()
                )
            );
        } else {
            return new self(
                sprintf(
                    'Contact with email: %s and contactListId: %s not found',
                    $getContactCommand->getEmail(),
                    $getContactCommand->getId()
                )
            );
        }


    }
}
