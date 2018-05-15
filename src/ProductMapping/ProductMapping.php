<?php
namespace GrShareCode\ProductMapping;

/**
 * Class ProductMapping
 * @package GrShareCode\ProductMapping
 */
class ProductMapping
{
    /** @var string */
    private $externalProductId;

    /** @var string */
    private $externalVariantId;

    /** @var string */
    private $grShopId;

    /** @var string */
    private $grProductId;

    /** @var string */
    private $grVariantId;

    /**
     * @param string $externalProductId
     * @param string $externalVariantId
     * @param string $grShopId
     * @param string $grProductId
     * @param string $grVariantId
     */
    public function __construct($externalProductId, $externalVariantId, $grShopId, $grProductId, $grVariantId)
    {
        $this->externalProductId = $externalProductId;
        $this->externalVariantId = $externalVariantId;
        $this->grShopId = $grShopId;
        $this->grProductId = $grProductId;
        $this->grVariantId = $grVariantId;
    }

    /**
     * @return string
     */
    public function getExternalProductId()
    {
        return $this->externalProductId;
    }

    /**
     * @return string
     */
    public function getExternalVariantId()
    {
        return $this->externalVariantId;
    }

    /**
     * @return string
     */
    public function getGrShopId()
    {
        return $this->grShopId;
    }

    /**
     * @return string
     */
    public function getGrProductId()
    {
        return $this->grProductId;
    }

    /**
     * @return string
     */
    public function getGrVariantId()
    {
        return $this->grVariantId;
    }

    /**
     * @return bool
     */
    public function variantExistsInGr()
    {
        return null !== $this->getGrVariantId() && null !== $this->getExternalProductId();
    }

    /**
     * @return bool
     */
    public function productExistsInGr()
    {
        return null !== $this->getGrProductId();
    }


}