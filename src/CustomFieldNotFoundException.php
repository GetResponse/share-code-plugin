<?php

namespace GrShareCode;

/**
 * Class CustomFieldNotFoundException
 * @package GrShareCode
 */
class CustomFieldNotFoundException extends GetresponseApiException
{
    /** @var string */
    private $customFieldId;

    /**
     * @param string $id
     * @return CustomFieldNotFoundException
     */
    public static function createWithCustomFieldId($id)
    {
        $exception = new self('Custom field ' . $id . ' not found', self::CUSTOM_FIELD_NOT_FOUND);
        $exception->customFieldId = $id;
        return $exception;
    }

    /**
     * @return string
     */
    public function getCustomFieldId()
    {
        return $this->customFieldId;
    }
}