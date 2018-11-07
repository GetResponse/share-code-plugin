<?php
namespace GrShareCode\CustomField;

/**
 * Class CustomField
 * @package GrShareCode\CustomField
 */
class CustomField
{
    const FIELD_TYPE = 'fieldType';
    const VALUE_TYPE = 'valueType';

    const FIELD_TYPE_TEXT = 'text';
    const VALUE_TYPE_STRING = 'string';

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $fieldType;

    /** @var string */
    private $valueType;

    /**
     * @param string $id
     * @param string $name
     * @param string $fieldType
     * @param string $valueType
     */
    public function __construct($id, $name, $fieldType, $valueType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->fieldType = $fieldType;
        $this->valueType = $valueType;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isTextField()
    {
        return $this->getFieldType() === self::FIELD_TYPE_TEXT
            && $this->getValueType() === self::VALUE_TYPE_STRING;
    }

    /**
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * @return string
     */
    public function getValueType()
    {
        return $this->valueType;
    }
}