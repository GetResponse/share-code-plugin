<?php
namespace GrShareCode\Product\Variant;

use GrShareCode\Product\Variant\Images\ImagesCollection;

/**
 * Class Variant
 * @package GrShareCode\Product\Variant
 */
class Variant
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var float */
    private $price;

    /** @var float */
    private $priceTax;

    /** @var string */
    private $sku;

    /** @var integer */
    private $quantity;

    /** @var string */
    private $url;

    /** @var string */
    private $description;
    /**
     * @var ImagesCollection
     */
    private $images;

    /**
     * @param string $id
     * @param string $name
     * @param float $price
     * @param float $priceTax
     * @param string $sku
     * @param int $quantity
     * @param string $url
     * @param string $description
     * @param ImagesCollection $images
     * @throws VariantException
     */
    public function __construct(
        $id,
        $name,
        $price,
        $priceTax,
        $sku,
        $quantity,
        $url,
        $description,
        ImagesCollection $images
    ) {
        $this->assertValidName($name);
        $this->assertValidPrice($price);
        $this->assertValidPriceTax($priceTax);
        $this->assertValidSku($sku);

        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->priceTax = $priceTax;
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->url = $url;
        $this->description = $description;
        $this->images = $images;
    }

    /**
     * @param string $name
     * @throws VariantException
     */
    private function assertValidName($name)
    {
        if (empty($name)) {
            throw VariantException::createForInvalidName();
        }
    }

    /**
     * @param float $price
     * @throws VariantException
     */
    private function assertValidPrice($price)
    {
        if (empty($price)) {
            throw VariantException::createForInvalidPrice();
        }
    }

    /**
     * @param float $priceTax
     * @throws VariantException
     */
    private function assertValidPriceTax($priceTax)
    {
        if (empty($priceTax)) {
            throw VariantException::createForInvalidPriceTax();
        }
    }

    /**
     * @param string $sku
     * @throws VariantException
     */
    private function assertValidSku($sku)
    {
        if (empty($sku)) {
            throw VariantException::createForInvalidSku();
        }
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
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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

}