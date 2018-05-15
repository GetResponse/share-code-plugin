<?php
namespace GrShareCode\Product;

/**
 * Class ProductVariant
 * @package GrShareCode\Product
 */
class ProductVariant
{
    /** @var string */
    private $name;

    /** @var float */
    private $price;

    /** @var float */
    private $priceTax;

    /** @var string */
    private $sku;

    /** @var int */
    private $quantity;

    /** @var string */
    private $url;

    /** @var string */
    private $description;

    /** @var ImagesCollection */
    private $images;

    /** @var string */
    private $externalId;

    /**
     * @param string $name
     * @param float $price
     * @param float $priceTax
     * @param string $sku
     * @param string $externalId
     * @param int $quantity
     * @param string $url
     * @param string $description
     * @param ImagesCollection $images
     */
    public function __construct(
        $name,
        $price,
        $priceTax,
        $sku,
        $externalId = '',
        $quantity = 0,
        $url = '',
        $description = '',
        ImagesCollection $images = null
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->priceTax = $priceTax;
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->url = $url;
        $this->description = $description;
        $this->images = $images;
        $this->externalId = $externalId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPriceTax()
    {
        return $this->priceTax;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return ImagesCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }
}
