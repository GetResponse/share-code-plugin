<?php
namespace GrShareCode\WebForm;

use GrShareCode\TypedCollection;

/**
 * Class WebFormCollection
 * @package GrShareCode\WebForm
 */
class WebFormCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType(WebForm::class);
    }

    /**
     * @param string $webFormId
     * @return WebForm
     * @throws FormNotFoundException
     */
    public function findOneById($webFormId)
    {
        /** @var WebForm $webForm */
        foreach ($this->getIterator() as $webForm) {
            if ($webFormId === $webForm->getWebFormId()) {
                return $webForm;
            }
        }

        throw FormNotFoundException::createWithId($webFormId);
    }
}