<?php
namespace GrShareCode\Cache;

/**
 * Interface CacheInterface
 * @package GrShareCode\Cache
 */
interface CacheInterface
{
    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     */
    public function set($key, $value, $ttl);

    /**
     * @param string $key
     * @return string
     */
    public function get($key);

    /**
     * @param string $key
     * @return bool
     */
    public function has($key);
}