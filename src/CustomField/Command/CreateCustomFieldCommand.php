<?php

namespace GrShareCode\CustomField\Command;

/**
 * Class CreateCustomFieldCommand
 * @package GrShareCode\CustomField\Command
 */
class CreateCustomFieldCommand
{
    /** @var string */
    private $name;
    /** @var string */
    private $type;
    /** @var bool */
    private $isHidden;
    /** @var array */
    private $values;

    /**
     * @param string $name
     * @param array $values
     * @param string $type
     * @param bool $isHidden
     */
    public function __construct($name, array $values, $type = 'text', $isHidden = false)
    {
        $this->name = $name;
        $this->values = $values;
        $this->type = $type;
        $this->isHidden = $isHidden;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->isHidden;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}