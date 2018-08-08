<?php
namespace GrShareCode\Tests\Integration;

use GrShareCode\Api\ApiType;
use GrShareCode\Api\ApiTypeException;
use GrShareCode\Api\UserAgentHeader;
use GrShareCode\GetresponseApi;
use PHPUnit_Framework_TestCase;

/**
 * Class BaseCaseTest
 * @package SalesforceSync\Tests\Integration
 */
abstract class BaseCaseTest extends PHPUnit_Framework_TestCase
{

    /** @var array */
    private $config;

    public function __construct()
    {
        define('ROOT_DIR', __DIR__ . '/../../');
        require_once ROOT_DIR . 'vendor/autoload.php';
        $this->config = include 'config.php';
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return GetresponseApi
     * @throws ApiTypeException
     */
    public function getGetresponseApi()
    {
        $apiType = ApiType::createForSMB();
        $userAgentHeader = new UserAgentHeader('ShareCode', 'ShareCode', 'ShareCode');

        return new GetresponseApi(
            $this->config['apiKey'], $apiType, $this->config['xappId'], $userAgentHeader
        );
    }

}