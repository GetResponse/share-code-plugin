<?php
namespace GrShareCode\Product\Variant\Images;

use GrShareCode\Validation\Assert\Assert;

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
        $this->setSrc($src);
        $this->setPosition($position);
    }

    /**
     * @param string $src
     */
    private function setSrc($src)
    {
        $message = 'Src in Image should be a not blank string';
        Assert::that($src, $message)->notBlank()->string();
        $this->src = $src;
    }

    /**
     * @param int $position
     */
    private function setPosition($position)
    {
        $message = 'Position in Image should be a not null integer';
        Assert::that($position, $message)->notNull()->integer();
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
