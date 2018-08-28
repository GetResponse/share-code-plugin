<?php
namespace GrShareCode\CustomField;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

/**
 * Class CustomFieldService
 * @package GrShareCode\CustomField
 */
class CustomFieldService
{
    const PER_PAGE = 100;

    /** @var GetresponseApiClient */
    private $getresponseApiClient;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     */
    public function __construct(GetresponseApiClient $getresponseApiClient)
    {
        $this->getresponseApiClient = $getresponseApiClient;
    }

    /**
     * @return CustomFieldCollection
     * @throws GetresponseApiException
     */
    public function getAllCustomFields()
    {
        $customFields = $this->getresponseApiClient->getCustomFields(1, self::PER_PAGE);

        $headers = $this->getresponseApiClient->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $customFields = array_merge($customFields, $this->getresponseApiClient->getCustomFields($page, self::PER_PAGE));
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
        return $this->getresponseApiClient->createCustomField([
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
        return $this->getresponseApiClient->deleteCustomField($customFieldId);
    }

    /**
     * @param string $customFieldName
     * @return null|array
     * @throws GetresponseApiException
     */
    public function getCustomFieldByName($customFieldName)
    {
        return $this->getresponseApiClient->getCustomFieldByName($customFieldName);
    }
}