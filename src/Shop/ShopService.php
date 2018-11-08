<?php
namespace GrShareCode\Shop;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\Shop\Command\AddShopCommand;
use GrShareCode\Shop\Command\DeleteShopCommand;

/**
 * Class ShopService
 * @package GrShareCode\Shop
 */
class ShopService
{
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
     * @return ShopsCollection
     * @throws GetresponseApiException
     */
    public function getAllShops()
    {
        $shops = $this->getresponseApiClient->getShops();
        $collection = new ShopsCollection();

        foreach ($shops as $field) {
            $collection->add(new Shop(
                $field['shopId'],
                $field['name']
            ));
        }

        return $collection;
    }

    /**
     * @param AddShopCommand $addShopCommand
     * @return string
     * @throws GetresponseApiException
     */
    public function addShop(AddShopCommand $addShopCommand)
    {
        return $this->getresponseApiClient->createShop([
            'name' => $addShopCommand->getName(),
            'locale' => $addShopCommand->getLocale(),
            'currency' => $addShopCommand->getCurrency()
        ]);
    }

    /**
     * @param DeleteShopCommand $deleteShopCommand
     * @throws GetresponseApiException
     */
    public function deleteShop(DeleteShopCommand $deleteShopCommand)
    {
        $this->getresponseApiClient->deleteShop($deleteShopCommand->getShopId());
    }
}
