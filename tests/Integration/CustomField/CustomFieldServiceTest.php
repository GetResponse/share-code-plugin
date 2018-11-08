<?php
namespace GrShareCode\Tests\Integration\CustomField;

use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\Tests\Integration\BaseCaseTest;

class CustomFieldServiceTest extends BaseCaseTest
{
    /** @var CustomFieldService */
    private $sut;

    public function setUp()
    {
        $this->sut = new CustomFieldService($this->getApiClient());
    }

    /**
     * @test
     */
    public function shouldFetchAllCustomFields()
    {
        $response = $this->sut->getAllCustomFields();
        self::assertGreaterThan(0, $response->count());
    }

}