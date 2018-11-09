<?php
namespace GrShareCode\Tests\Integration\CustomField;

use GrShareCode\Api\Authorization\ApiTypeException;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\Tests\Integration\BaseCaseTest;

class CustomFieldServiceTest extends BaseCaseTest
{
    /** @var CustomFieldService */
    private $sut;

    /**
     * @throws ApiTypeException
     */
    public function setUp()
    {
        $this->sut = new CustomFieldService($this->getApiClient());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldFetchAllCustomFields()
    {
        $response = $this->sut->getAllCustomFields();
        self::assertGreaterThan(0, $response->count());
    }

}