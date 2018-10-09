<?php
namespace GrShareCode\Contact;

use GrShareCode\GrShareCodeException;

/**
 * Class ContactNotFoundException
 * @package GrShareCode\Cart
 */
class ContactNotFoundException extends GrShareCodeException
{
    /**
     * @param string $email
     * @param string $contactListId
     * @return ContactNotFoundException
     */
    public static function withEmailAndContactListId($email, $contactListId)
    {
        return new self(sprintf('Contact with email: %s and contactListId: %s not found', $email, $contactListId));
    }
}
