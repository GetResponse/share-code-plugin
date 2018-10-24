<?php
namespace GrShareCode\Contact\Command;

class FindContactCommand
{
    /** @var string */
    private $email;
    /** @var string */
    private $listId;
    /** @var bool */
    private $withCustoms;

    /**
     * @param string $email
     * @param string $listId
     * @param bool $withCustoms
     */
    public function __construct($email, $listId, $withCustoms = false)
    {
        $this->email = $email;
        $this->listId = $listId;
        $this->withCustoms = $withCustoms;
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

    /**
     * @return bool
     */
    public function withCustoms()
    {
        return $this->withCustoms;
    }
}