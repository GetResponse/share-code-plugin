<?php
namespace GrShareCode\Product\Variant\Images;

/**
 * Class Image
 * @package GrShareCode\Product\Variant\Images
 */
class Image
{
    /** @var string */
    private $src;

    /** @var int */
    private $position;

    /**
     * @param string $src
     * @param int $position
     */
    public function __construct($src, $position)
    {
        $this->src = $src;
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}