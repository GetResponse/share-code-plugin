<?php
namespace GrShareCode\Contact\Command;

/**
 * Class UnsubscribeContactsCommand
 * @package GrShareCode\Contact\Command
 */
class UnsubscribeContactsCommand
{
    /** @var string */
    private $email;

    /**
     * @param string $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}