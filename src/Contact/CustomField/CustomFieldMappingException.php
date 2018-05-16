<?php
namespace GrShareCode\Contact\CustomField;

use GrShareCode\GrShareCodeException;

/**
 * Class CustomFieldMappingException
 * @package GrShareCode\Contact\CustomField
 */
class CustomFieldMappingException extends GrShareCodeException
{
    /**
     * @param string $externalCustomFieldName
     * @return CustomFieldMappingException
     */
    public static function createForInvalidExternalCustomFieldName($externalCustomFieldName)
    {
        return new self('Invalid external customField name: ' . $externalCustomFieldName);
    }

    /**
     * @param string $status
     * @return CustomFieldMappingException
     */
    public static function createForInvalidStatus($status)
    {
        return new self('Invalid status: ' . $status);
    }
}