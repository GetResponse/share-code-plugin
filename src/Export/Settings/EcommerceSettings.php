<?php
namespace GrShareCode\Export\Settings;

/**
 * Class Config
 * @package GrShareCode\Export
 */
class EcommerceSettings
{
    /** @var boolean */
    private $ecommerceEnabled;

    /** @var string */
    private $shopId;

    /**
     * @param boolean $ecommerceEnabled
     * @param string $shopId
     */
    public function __construct($ecommerceEnabled, $shopId = null)
    {
        $this->ecommerceEnabled = $ecommerceEnabled;
        $this->shopId = $shopId;
    }

    /**
     * @return bool
     */
    public function isEcommerceEnabled()
    {
        return $this->ecommerceEnabled;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }


}