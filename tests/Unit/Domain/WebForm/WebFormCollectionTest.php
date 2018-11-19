<?php
namespace GrShareCode\Tests\Unit\Domain\WebForm;

use GrShareCode\Tests\Unit\BaseTestCase;
use GrShareCode\WebForm\FormNotFoundException;
use GrShareCode\WebForm\WebForm;
use GrShareCode\WebForm\WebFormCollection;

class WebFormCollectionTest extends BaseTestCase
{
    /** @var WebFormCollection */
    private $webFormCollection;
    /** @var WebForm */
    private $webform1;
    /** @var WebForm */
    private $webform2;

    public function setUp()
    {
        $this->webFormCollection = new WebFormCollection();

        $this->webform1 = new WebForm(
            'id1',
            'name',
            'https://example.com/js.js',
            'campaign_name',
            WebForm::STATUS_ENABLED,
            WebForm::VERSION_V1
        );

        $this->webform2 = new WebForm(
            'id2',
            'name2',
            'https://example.com/js2.js',
            'campaign_name',
            WebForm::STATUS_ENABLED,
            WebForm::VERSION_V1
        );

        $this->webFormCollection->add($this->webform1);
        $this->webFormCollection->add($this->webform2);
    }

    /**
     * @test
     * @throws FormNotFoundException
     */
    public function shouldFindWebFormById()
    {
        self::assertEquals(
            $this->webform1,
            $this->webFormCollection->findOneById($this->webform1->getWebFormId())
        );
    }

    /**
     * @test
     * @throws FormNotFoundException
     */
    public function shouldThrowExceptionWhenFormDoesntExist()
    {
        $this->expectException(FormNotFoundException::class);
        $this->webFormCollection->findOneById('id-not-exists');
    }
}