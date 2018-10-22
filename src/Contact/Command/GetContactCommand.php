<?php
namespace GrShareCode\Contact\Command;

/**
 * Class GetContactCommand
 * @package GrShareCode\Contact\Command
 */
class GetContactCommand
{
    /** @var string */
    private $email;
    /** @var string */
    private $listId;
    /** @var string */
    private $id;

    /**
     * @param $id
     * @param string $email
     * @param string $listId
     */
    public function __construct($id = null, $email = null, $listId = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->listId = $listId;
    }

    public static function createWithId($id)
    {
        return new GetContactCommand($id);
    }

    public static function createWithEmailAndListId($email, $listId)
    {
        return new GetContactCommand(null, $email, $listId);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
    }
}