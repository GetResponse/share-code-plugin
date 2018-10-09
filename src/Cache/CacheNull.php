<?php
namespace GrShareCode\Cache;

/**
 * Class CacheNull
 * @package GrShareCode\Cache
 */
class CacheNull implements CacheInterface
{
    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     */
    public function set($key, $value, $ttl = 0)
    {

    }

    /**
     * @param string $key
     * @return null|string
     */
    public function get($key)
    {
        return null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return false;
    }
}