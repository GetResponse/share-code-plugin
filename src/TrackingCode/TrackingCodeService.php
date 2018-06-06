<?php
namespace GrShareCode\TrackingCode;

use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;

/**
 * Class TrackingCodeService
 * @package GrShareCode\TrackingCode
 */
class TrackingCodeService
{
    /** @var GetresponseApi */
    private $getresponseApi;

    /**
     * @param GetresponseApi $getresponseApi
     */
    public function __construct(GetresponseApi $getresponseApi)
    {
        $this->getresponseApi = $getresponseApi;
    }

    /**
     * @return TrackingCode|string
     * @throws GetresponseApiException
     */
    public function getTrackingCode()
    {
        $accountFeatures = $this->getresponseApi->getAccountFeatures();
        $featureEnabled = $this->isTackingFeatureEnabled($accountFeatures);
        $trackingCodeSnippet = $this->getresponseApi->getTrackingCodeSnippet();

        return new TrackingCode($featureEnabled, $trackingCodeSnippet);
    }

    /**
     * @param array $accountFeatures
     * @return bool
     */
    private function isTackingFeatureEnabled(array $accountFeatures)
    {
        return isset($accountFeatures['feature_tracking']) && true === $accountFeatures['feature_tracking'];
    }
}