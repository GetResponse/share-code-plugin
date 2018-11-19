<?php
namespace GrShareCode\Contact\Command;

/**
 * Class GetContactCommand
 * @package GrShareCode\Contact\Command
 */
class GetContactCommand
{
    /** @var string */
    private $id;
    /** @var bool */
    private $withCustoms;

    /**
     * @param string $id
     * @param bool $withCustoms
     */
    public function __construct($id, $withCustoms = false)
    {
        $this->id = $id;
        $this->withCustoms = $withCustoms;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function withCustoms()
    {
        return $this->withCustoms;
    }
}