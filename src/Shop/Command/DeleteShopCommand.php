<?php
namespace GrShareCode\Shop\Command;

/**
 * Class DeleteShopCommand
 * @package GrShareCode\Shop\Command
 */
class DeleteShopCommand
{
    /** @var string */
    private $shopId;

    /**
     * @param string $shopId
     */
    public function __construct($shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }
}