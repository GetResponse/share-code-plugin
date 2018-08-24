<?php
namespace GrShareCode\WebForm;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

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
        $webForms[] = $this->getresponseApiClient->getWebForms(1, self::PER_PAGE);
        $headers = $this->getresponseApiClient->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $webForms[] = $this->getresponseApiClient->getWebForms($page, self::PER_PAGE);
        }

        $webFormsList = call_user_func_array('array_merge', $webForms);

        foreach ($webFormsList as $webForm) {

            $collection->add(new WebForm(
                $webForm['webformId'],
                $webForm['name'],
                $webForm['scriptUrl'],
                $webForm['campaign']['name'],
                $webForm['status']
            ));
        }
    }

    /**
     * @param WebFormCollection $collection
     * @throws GetresponseApiException
     */
    private function addForms(WebFormCollection $collection)
    {
        $forms[] = $this->getresponseApiClient->getForms(1, self::PER_PAGE);
        $headers = $this->getresponseApiClient->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $forms[] = $this->getresponseApiClient->getForms($page, self::PER_PAGE);
        }

        $formsList = call_user_func_array('array_merge', $forms);

        foreach ($formsList as $form) {

            $collection->add(new WebForm(
                $form['webformId'],
                $form['name'],
                $form['scriptUrl'],
                $form['campaign']['name'],
                $form['status'] === 'published' ? WebForm::STATUS_ENABLED : WebForm::STATUS_DISABLED
            ));
        }
    }
}