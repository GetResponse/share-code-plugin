<?php
namespace GrShareCode;

/**
 * Interface Matcher
 * @package GrShareCode
 */
interface Matcher
{
    /**
     * @param mixed $item
     * @return bool
     */
    public function matches($item);
}