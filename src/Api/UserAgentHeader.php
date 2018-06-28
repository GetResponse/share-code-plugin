<?php
namespace GrShareCode\Api;

/**
 * Class UserAgentHeader
 * @package GrShareCode\Api
 */
class UserAgentHeader
{
    const SEPARATOR = '/';

    /** @var string */
    private $serviceName;

    /** @var string */
    private $serviceVersion;

    /** @var string */
    private $pluginVersion;

    /** @var string */
    private $phpVersion;

    /**
     * @param string $serviceName
     * @param string $serviceVersion
     * @param string $pluginVersion
     */
    public function __construct($serviceName, $serviceVersion, $pluginVersion)
    {
        $this->serviceName = $serviceName;
        $this->serviceVersion = $serviceVersion;
        $this->pluginVersion = $pluginVersion;
        $this->phpVersion = PHP_VERSION;
    }

    /**
     * @return string
     */
    public function getUserAgentInfo()
    {
        return $this->serviceName . self::SEPARATOR . $this->serviceVersion . self::SEPARATOR . $this->pluginVersion . self::SEPARATOR . $this->phpVersion;
    }

}