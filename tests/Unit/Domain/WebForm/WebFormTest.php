<?php
namespace GrShareCode\Tests\Unit\Domain\WebForm;

use GrShareCode\Tests\Unit\BaseTestCase;
use GrShareCode\Validation\Assert\InvalidArgumentException;
use GrShareCode\WebForm\WebForm;

/**
 * Class WebFormTest
 * @package GrShareCode\Tests\Unit\Domain\WebForm
 */
class WebFormTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldBeInstantiatable()
    {
        $webForm = new WebForm(
            'id',
            'name',
            'url',
            'campaign_name',
            WebForm::STATUS_ENABLED,
            WebForm::VERSION_V1
        );

        self::assertEquals('id', $webForm->getWebFormId());
        self::assertEquals('name', $webForm->getName());
        self::assertEquals('url', $webForm->getScriptUrl());
        self::assertEquals('campaign_name', $webForm->getCampaignName());
        self::assertEquals(WebForm::STATUS_ENABLED, $webForm->getStatus());
        self::assertEquals(WebForm::VERSION_V1, $webForm->getVersion());
        self::assertEquals(true, $webForm->isEnabled());
    }


    /**
     * @test
     */
    public function shouldThrowExceptionWhenWrongStatus()
    {
        $this->expectException(InvalidArgumentException::class);

        $webForm = new WebForm(
            'id',
            'name',
            'url',
            'campaign_name',
            'not-existing-status-code',
            'v1'
        );
    }
}