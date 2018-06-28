<?php
namespace GrShareCode\Shop;

/**
 * Class AddShopCommnad
 * @package GrShareCode\Shop
 */
class AddShopCommand
{
    /** @var string */
    private $name;

    /** @var string */
    private $locale;

    /** @var string */
    private $currency;

    /**
     * @param string $name
     * @param string $locale
     * @param string $currency
     */
    public function __construct($name, $locale, $currency)
    {
        $this->name = $name;
        $this->locale = $locale;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }


}