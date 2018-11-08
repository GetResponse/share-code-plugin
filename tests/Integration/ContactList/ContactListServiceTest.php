<?php
namespace GrShareCode\Tests\Integration\ContactList;

use GrShareCode\ContactList\ContactListService;
use GrShareCode\Tests\Integration\BaseCaseTest;

/**
 * Class ContactListServiceTest
 * @package GrShareCode\Tests\Integration\Contact
 */
class ContactListServiceTest extends BaseCaseTest
{
    /** @var ContactListService */
    private $sut;

    public function setUp()
    {
        $this->sut = new ContactListService($this->getApiClient());
    }

    /**
     * @test
     */
    public function shouldGetAllAutorespondersBelongsToSpecificCampaign()
    {
        $response = $this->sut->getAutoresponders($this->getConfig()['contactListId']);
        self::assertGreaterThan(0, $response->count());
    }

    /**
     * @test
     */
    public function shouldGetAllAutoresponders()
    {
        $response = $this->sut->getAutoresponders();
        echo $response->count();
        self::assertGreaterThan(0, $response->count());
    }
}