<?php
namespace GrShareCode\Contact\CustomFieldMapping;

use GrShareCode\Validation\Assert\Assert;

/**
 * Class CustomFieldMapping
 * @package GrShareCode\Contact\CustomField
 */
class CustomFieldMapping
{
    const FIELD_EMAIL = 'email';
    const FIELD_FIRSTNAME = 'firstname';
    const FIELD_LASTNAME = 'lastname';
    const FIELD_STREET = 'street';
    const FIELD_POSTCODE = 'postcode';
    const FIELD_CITY = 'city';
    const FIELD_TELEPHONE = 'telephone';
    const FIELD_COUNTRY = 'country';
    const FIELD_BIRTHDAY = 'birthday';
    const FIELD_COMPANY = 'company';

    const FIELDS_ALL = [
        self::FIELD_EMAIL,
        self::FIELD_FIRSTNAME,
        self::FIELD_LASTNAME,
        self::FIELD_STREET,
        self::FIELD_POSTCODE,
        self::FIELD_CITY,
        self::FIELD_TELEPHONE,
        self::FIELD_COUNTRY,
        self::FIELD_BIRTHDAY,
        self::FIELD_COMPANY
    ];

    /** @var string */
    private $externalCustomFieldName;

    /** @var string */
    private $grCustomFieldId;

    /** @var string */
    private $externalCustomFieldValue;

    /**
     * @param string $externalCustomFieldName
     * @param string $externalCustomFieldValue
     * @param string $grCustomFieldId
     */
    public function __construct($externalCustomFieldName, $externalCustomFieldValue, $grCustomFieldId)
    {
        $this->setExternalCustomFieldName($externalCustomFieldName);
        $this->setExternalCustomFieldValue($externalCustomFieldValue);
        $this->setGrCustomFieldId($grCustomFieldId);
    }

    /**
     * @param string $externalCustomFieldName
     */
    private function setExternalCustomFieldName($externalCustomFieldName)
    {
        $message = 'External custom field name in CustomFieldMapping has invalid value - ' . $externalCustomFieldName;
        Assert::that($externalCustomFieldName, $message)->choice(self::FIELDS_ALL);
        $this->externalCustomFieldName = $externalCustomFieldName;
    }

    /**
     * @param string $externalCustomFieldValue
     */
    private function setExternalCustomFieldValue($externalCustomFieldValue)
    {
        $message = 'External custom field value should be a not blank string';
        Assert::that($externalCustomFieldValue, $message)->notBlank()->string();
        $this->externalCustomFieldValue = $externalCustomFieldValue;
    }

    /**
     * @param int $grCustomFieldId
     */
    private function setGrCustomFieldId($grCustomFieldId)
    {
        $message = 'Gr custom field ID should be a not null string';
        Assert::that($grCustomFieldId, $message)->notNull()->string();
        $this->grCustomFieldId = $grCustomFieldId;
    }

    /**
     * @return string
     */
    public function getExternalCustomFieldValue()
    {
        return $this->externalCustomFieldValue;
    }

    /**
     * @return string
     */
    public function getExternalCustomFieldName()
    {
        return $this->externalCustomFieldName;
    }

    /**
     * @return string
     */
    public function getGrCustomFieldId()
    {
        return $this->grCustomFieldId;
    }

}