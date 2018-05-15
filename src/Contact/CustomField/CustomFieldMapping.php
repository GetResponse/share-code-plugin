<?php
namespace GrShareCode\Contact\CustomField;

/**
 * Class CustomFieldMapping
 * @package GrShareCode\Contact\CustomField
 */
class CustomFieldMapping
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const STATUSES_ALL = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

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
    private $status;

    /** @var string */
    private $externalCustomFieldValue;

    /**
     * @param string $externalCustomFieldName
     * @param string $externalCustomFieldValue
     * @param string $grCustomFieldId
     * @param string $status
     * @throws CustomFieldMappingException
     */
    public function __construct($externalCustomFieldName, $externalCustomFieldValue, $grCustomFieldId, $status)
    {
        $this->assertCustomFieldNameValid($externalCustomFieldName);
        $this->assertStatusValid($status);

        $this->externalCustomFieldName = $externalCustomFieldName;
        $this->externalCustomFieldValue = $externalCustomFieldValue;
        $this->grCustomFieldId = $grCustomFieldId;
        $this->status = $status;
    }

    /**
     * @param $externalCustomFieldName
     * @throws CustomFieldMappingException
     */
    private function assertCustomFieldNameValid($externalCustomFieldName)
    {
        if (!in_array($externalCustomFieldName, self::FIELDS_ALL, true)) {
            throw CustomFieldMappingException::createForInvalidExternalCustomFieldName($externalCustomFieldName);
        }
    }

    /**
     * @param string $status
     * @throws CustomFieldMappingException
     */
    private function assertStatusValid($status)
    {
        if (!in_array($status, self::STATUSES_ALL, true)) {
            throw CustomFieldMappingException::createForInvalidStatus($status);
        }
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

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

}