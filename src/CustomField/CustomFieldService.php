<?php
namespace GrShareCode\CustomField;

use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;

/**
 * Class CustomFieldService
 * @package GrShareCode\CustomField
 */
class CustomFieldService
{
    const PER_PAGE = 100;

    /** @var GetresponseApi */
    private $getresponseApi;

    /**
     * @param GetresponseApi $getresponseApi
     */
    public function __construct(GetresponseApi $getresponseApi)
    {
        $this->getresponseApi = $getresponseApi;
    }

    /**
     * @return CustomFieldCollection
     * @throws GetresponseApiException
     */
    public function getAllCustomFields()
    {
        $customFields = $this->getresponseApi->getCustomFields(1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $customFields = array_merge($customFields, $this->getresponseApi->getCustomFields($page, self::PER_PAGE));
        }

        $collection = new CustomFieldCollection();

        foreach ($customFields as $field) {
            $collection->add(new CustomField(
                $field['customFieldId'],
                $field['name']
            ));
        }

        return $collection;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @param bool $hidden
     * @return array
     * @throws GetresponseApiException
     */
    public function createCustomField($name, $value, $type = 'text', $hidden = false)
    {
        return $this->getresponseApi->createCustomField([
            'name' => $name,
            'type' => $type,
            'hidden' => $hidden,
            'values' => [$value]
        ]);
    }

    /**
     * @param string $customFieldId
     * @return string
     * @throws GetresponseApiException
     */
    public function deleteCustomFieldById($customFieldId)
    {
        return $this->getresponseApi->deleteCustomField($customFieldId);
    }

    /**
     * @param string $customFieldName
     * @return null|array
     * @throws GetresponseApiException
     */
    public function getCustomFieldByName($customFieldName)
    {
        return $this->getresponseApi->getCustomFieldByName($customFieldName);
    }
}