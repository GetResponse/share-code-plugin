<?php
namespace GrShareCode\Api;

/**
 * Interface AuthorizationInterface
 * @package GrShareCode\Api
 */
interface AuthorizationInterface
{
    /**
     * @return string
     */
    public function getAuthorizationHeader();

    /**
     * @return string
     */
    public function getAccessToken();
}
