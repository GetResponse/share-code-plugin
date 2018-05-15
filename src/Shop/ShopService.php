<?php
namespace GrShareCode\Shop;

use GrShareCode\GetresponseApi;

/**
 * Class ShopService
 * @package GrShareCode\Shop
 */
class ShopService
{
    const PER_PAGE = 100;

    /** @var GetresponseApi */
    private $getresponseApi;

    /**
     * @param GetresponseApi $getresponseApi
     */
    public function __construct(GetresponseApi $getresponseApi)
    {
        $this->getresponseApi = $getresponseApi;
    }

    /**
     * @return array|ShopsCollection
     */
    public function getAllShops()
    {
        $shops = $this->getresponseApi->getShops(1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $shops = array_merge($shops,  $this->getresponseApi->getShops($page, self::PER_PAGE));
        }

        $collection = new ShopsCollection();

        foreach ($shops as $field) {
            $collection->add(new Shop(
                $field['shopId'],
                $field['name']
            ));
        }

        return $collection;
    }
}
