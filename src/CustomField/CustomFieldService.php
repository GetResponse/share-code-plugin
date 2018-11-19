<?php
namespace GrShareCode\CustomField;

use GrShareCode\CustomField\Command\CreateCustomFieldCommand;
use GrShareCode\CustomField\CustomFieldFilter\CustomFieldForMappingFilter;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;

/**
 * Class CustomFieldService
 * @package GrShareCode\CustomField
 */
class CustomFieldService
{
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
        return CustomFieldCollection::fromApiResponse($this->getresponseApiClient->getCustomFields());
    }

    /**
     * @return CustomFieldCollection
     * @throws GetresponseApiException
     */
    public function getCustomFieldsForMapping()
    {
        $customFieldCollection = CustomFieldCollection::fromApiResponse($this->getresponseApiClient->getCustomFields());
        return $customFieldCollection->filter(new CustomFieldForMappingFilter());
    }

    /**
     * @param CreateCustomFieldCommand $createCustomFieldCommand
     * @return array
     * @throws GetresponseApiException
     */
    public function createCustomField(CreateCustomFieldCommand $createCustomFieldCommand)
    {
        return $this->getresponseApiClient->createCustomField([
            'name' => $createCustomFieldCommand->getName(),
            'type' => $createCustomFieldCommand->getType(),
            'hidden' => $createCustomFieldCommand->isHidden(),
            'values' => $createCustomFieldCommand->getValues()
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