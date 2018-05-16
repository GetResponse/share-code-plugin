<?php
namespace GrShareCode\Product\Variant;

use GrShareCode\Product\Variant\Images\Image;
use GrShareCode\Product\Variant\Images\ImagesCollection;

/**
 * Class Variant
 * @package GrShareCode\Product\Variant
 */
class Variant
{
    /** @var string */
    private $externalId;

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

    /** @var ImagesCollection */
    private $images;

    /**
     * @param string $externalId
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
        $externalId,
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

        $this->externalId = $externalId;
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
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return array
     */
    public function toRequestArray()
    {
        $result = [
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'priceTax' => $this->getPriceTax(),
            'sku' => $this->getSku()
        ];


        /** @var Image $image */
        foreach ($this->getImages()->getIterator() as $image) {

            $result['images'] = [
                'src' => $image->getSrc(),
                'position' => $image->getPosition()
            ];
        }

        return $result;
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
     * @return ImagesCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param string $grVariantId
     * @return array
     */
    public function toRequestArrayWithVariantId($grVariantId)
    {
        $result = [
            'variantId' => $grVariantId,
            'price' => $this->getPrice(),
            'priceTax' => $this->getPriceTax(),
            'quantity' => $this->getQuantity()
        ];

        /** @var Image $image */
        foreach ($this->getImages()->getIterator() as $image) {

            $result['images'] = [
                'src' => $image->getSrc(),
                'position' => $image->getPosition()
            ];
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

}