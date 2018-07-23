<?php
namespace GrShareCode\Product\Variant;

use GrShareCode\Product\Variant\Images\Image;
use GrShareCode\Product\Variant\Images\ImagesCollection;
use GrShareCode\Validation\Assert\Assert;

/**
 * Class Variant
 * @package GrShareCode\Product\Variant
 */
class Variant
{
    const DESCRIPTION_MAX_LENGTH = 1000;

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
     * @param int $externalId
     * @param string $name
     * @param float $price
     * @param float $priceTax
     * @param string $sku
     */
    public function __construct($externalId, $name, $price, $priceTax, $sku)
    {
        $this->setExternalId($externalId);
        $this->setName($name);
        $this->setPrice($price);
        $this->setPriceTax($priceTax);
        $this->setSku($sku);
    }

    /**
     * @param int $externalId
     */
    private function setExternalId($externalId)
    {
        Assert::that($externalId)->notNull()->integer();
        $this->externalId = $externalId;
    }

    /**
     * @param string $name
     */
    private function setName($name)
    {
        Assert::that($name)->notBlank()->string();
        $this->name = $name;
    }

    /**
     * @param float $price
     */
    private function setPrice($price)
    {
        Assert::that($price)->notNull()->float();
        $this->price = $price;
    }

    /**
     * @param float $priceTax
     */
    private function setPriceTax($priceTax)
    {
        Assert::that($priceTax)->notNull()->float();
        $this->priceTax = $priceTax;
    }

    /**
     * @param string $sku
     */
    private function setSku($sku)
    {
        Assert::that($sku)->notBlank()->string();
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        Assert::that($url)->notNull()->string();
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        Assert::that($description)->notBlank()->string()->maxLength(self::DESCRIPTION_MAX_LENGTH);
        $this->description = $description;

        return $this;
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
            'externalId' => $this->getExternalId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'priceTax' => $this->getPriceTax(),
            'sku' => $this->getSku(),
            'quantity' => $this->getQuantity(),
            'url' => $this->getUrl(),
            'description' => $this->getDescription()
        ];


        /** @var Image $image */
        foreach ($this->getImages()->getIterator() as $image) {

            $result['images'][] = [
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
     * @param ImagesCollection $images
     * @return $this
     */
    public function setImages(ImagesCollection $images)
    {
        $this->images = $images;

        return $this;
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
        foreach ($this->getImages() as $image) {

            $result['images'][] = [
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

    /**
     * @param integer $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        Assert::that($quantity)->notNull()->integer();
        $this->quantity = $quantity;

        return $this;
    }

}