<?php
namespace GrShareCode\WebForm\Command;

/**
 * Class GetWebFormCommand
 * @package GrShareCode\WebForm\Command
 */
class GetWebFormCommand
{
    /** @var string */
    private $id;
    /** @var string */
    private $version;

    /**
     * @param string $id
     * @param string $version
     */
    public function __construct($id, $version)
    {
        $this->id = $id;
        $this->version = $version;
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
    public function getVersion()
    {
        return $this->version;
    }
}