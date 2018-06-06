<?php
namespace GrShareCode\TrackingCode;

/**
 * Class TrackingCode
 * @package GrShareCode\TrackingCode
 */
class TrackingCode
{
    /** @var string */
    private $snippet;

    /** @var boolean */
    private $featureEnabled;

    /**
     * @param $featureEnabled
     * @param string $snippet
     */
    public function __construct($featureEnabled, $snippet)
    {
        $this->featureEnabled = $featureEnabled;
        $this->snippet = $snippet;
    }

    /**
     * @return bool
     */
    public function isFeatureEnabled()
    {
        return $this->featureEnabled;
    }

    /**
     * @return string
     */
    public function getSnippet()
    {
        return $this->snippet;
    }


}