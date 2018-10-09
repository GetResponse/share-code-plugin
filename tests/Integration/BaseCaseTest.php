<?php
namespace GrShareCode\Tests\Integration;

use GrShareCode\Api\Authorization;
use GrShareCode\Api\ApiTypeException;
use GrShareCode\Api\OauthAuthorization;
use GrShareCode\Api\UserAgentHeader;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiClient;
use PHPUnit_Framework_TestCase;

/**
 * Class BaseCaseTest
 * @package SalesforceSync\Tests\Integration
 */
abstract class BaseCaseTest extends PHPUnit_Framework_TestCase
{
    /** @var array */
    private $config;

    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $dbRepositoryMock;


    public function __construct()
    {
        define('ROOT_DIR', __DIR__ . '/../../');
        require_once ROOT_DIR . 'vendor/autoload.php';
//        $this->config = include 'config.php';
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)->getMock();
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return GetresponseApiClient
     * @throws ApiTypeException
     */
    public function getApiClient()
    {
        $authorization = new OauthAuthorization('accessToken', 'refreshToken', Authorization::SMB, '');
        $userAgentHeader = new UserAgentHeader('ShareCode', 'ShareCode', 'ShareCode');

        return new GetresponseApiClient(
            new GetresponseApi($authorization, $this->config['xappId'], $userAgentHeader),
            $this->dbRepositoryMock
        );
    }

}