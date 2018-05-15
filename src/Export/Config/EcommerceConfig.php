<?php
namespace GrShareCode\Export\Config;

/**
 * Class Config
 * @package GrShareCode\Export
 */
class EcommerceConfig
{
    /** @var boolean */
    private $ecommerceEnabled;

    /** @var string */
    private $shopId;

    /**
     * @param boolean $ecommerceEnabled
     * @param string $shopId
     */
    public function __construct($ecommerceEnabled, $shopId)
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