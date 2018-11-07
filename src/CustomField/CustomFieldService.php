<?php
namespace GrShareCode\CustomField;

use GrShareCode\CustomField\CustomFieldFilter\CustomFieldForMappingFilter;
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
        return CustomFieldCollection::fromApiResponse($this->getCustomFields());
    }

    /**
     * @return CustomFieldCollection
     * @throws GetresponseApiException
     */
    public function getCustomFieldsForMapping()
    {
        $customFieldCollection = CustomFieldCollection::fromApiResponse($this->getCustomFields());

        return $customFieldCollection->filterBy(new CustomFieldForMappingFilter());
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @param bool $hidden
     * @return array
     * @throws GetresponseApiException
     */
    public function createCustomField($name, $value, $type = CustomField::FIELD_TYPE_TEXT, $hidden = false)
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

    /**
     * @return array
     * @throws GetresponseApiException
     */
    private function getCustomFields()
    {
        $page = 1;

        do {
            $customFields[] = $this->getresponseApiClient->getCustomFields($page, self::PER_PAGE);
            $page++;
        } while ($page <= $this->getresponseApiClient->getHeaders()['TotalPages']);

        return call_user_func_array('array_merge', $customFields);
    }
}