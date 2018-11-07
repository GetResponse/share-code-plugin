<?php
namespace GrShareCode\Tests\Integration;

use GrShareCode\Api\ApiKeyAuthorization;
use GrShareCode\Api\Authorization;
use GrShareCode\Api\ApiTypeException;
use GrShareCode\Api\UserAgentHeader;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiClient;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseCaseTest
 * @package SalesforceSync\Tests\Integration
 */
abstract class BaseCaseTest extends TestCase
{
    /** @var array */
    private $config;

    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $dbRepositoryMock;


    public function __construct()
    {
        $this->config = include 'config.php';
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
        $authorization = new ApiKeyAuthorization($this->config['apiKey'], Authorization::SMB);
        $userAgentHeader = new UserAgentHeader('ShareCode', 'ShareCode', 'ShareCode');

        return new GetresponseApiClient(
            new GetresponseApi($authorization, $this->config['xappId'], $userAgentHeader),
            $this->dbRepositoryMock
        );
    }

}