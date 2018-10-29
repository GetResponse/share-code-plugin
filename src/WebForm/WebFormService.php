<?php
namespace GrShareCode\WebForm;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\WebForm\Command\GetWebFormCommand;

/**
 * Class WebFormService
 * @package GrShareCode\WebForm
 */
class WebFormService
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
     * @return WebFormCollection
     * @throws GetresponseApiException
     */
    public function getAllWebForms()
    {
        $collection = new WebFormCollection();
        $this->addWebForms($collection);
        $this->addForms($collection);

        return $collection;
    }

    /**
     * @param WebFormCollection $collection
     * @throws GetresponseApiException
     */
    private function addWebForms(WebFormCollection $collection)
    {
        $webForms = $this->getresponseApiClient->getWebForms(1, self::PER_PAGE);
        $headers = $this->getresponseApiClient->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $webForms = array_merge($webForms, $this->getresponseApiClient->getWebForms($page, self::PER_PAGE));
        }

        foreach ($webForms as $webForm) {

            $collection->add(new WebForm(
                $webForm['webformId'],
                $webForm['name'],
                $webForm['scriptUrl'],
                $webForm['campaign']['name'],
                $webForm['status'],
                WebForm::VERSION_V1
            ));
        }
    }

    /**
     * @param WebFormCollection $collection
     * @throws GetresponseApiException
     */
    private function addForms(WebFormCollection $collection)
    {
        $forms = $this->getresponseApiClient->getForms(1, self::PER_PAGE);
        $headers = $this->getresponseApiClient->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $forms = array_merge($forms, $this->getresponseApiClient->getForms($page, self::PER_PAGE));
        }

        foreach ($forms as $form) {

            $collection->add(new WebForm(
                $form['webformId'],
                $form['name'],
                $form['scriptUrl'],
                $form['campaign']['name'],
                $form['status'] === 'published' ? WebForm::STATUS_ENABLED : WebForm::STATUS_DISABLED,
                WebForm::VERSION_V2
            ));
        }
    }

    /**
     * @param GetWebFormCommand $getWebFormCommand
     * @return WebForm
     * @throws GetresponseApiException
     */
    public function getWebFormById(GetWebFormCommand $getWebFormCommand)
    {
        if (WebForm::VERSION_V2 == $getWebFormCommand->getVersion()) {
            $form = $this->getresponseApiClient->getFormById($getWebFormCommand->getId());
            $status = 'published' ? WebForm::STATUS_ENABLED : WebForm::STATUS_DISABLED;
            $version = WebForm::VERSION_V2;
        } else {
            $form = $this->getresponseApiClient->getWebFormById($getWebFormCommand->getId());
            $status = $form['status'];
            $version = WebForm::VERSION_V1;
        }

        return new WebForm(
            $form['webformId'],
            $form['name'],
            $form['scriptUrl'],
            $form['campaign']['name'],
            $status,
            $version
        );
    }
}