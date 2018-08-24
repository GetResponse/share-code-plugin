<?php
namespace GrShareCode\TrackingCode;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

/**
 * Class TrackingCodeService
 * @package GrShareCode\TrackingCode
 */
class TrackingCodeService
{
    /** @var GetresponseApiClient */
    private $getresponseApiClient;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     */
    public function __construct(GetresponseApiClient $getresponseApiClient)
    {
        $this->getresponseApiClient = $getresponseApiClient;
    }

    /**
     * @return TrackingCode|string
     * @throws GetresponseApiException
     */
    public function getTrackingCode()
    {
        $accountFeatures = $this->getresponseApiClient->getAccountFeatures();
        $featureEnabled = $this->isTackingFeatureEnabled($accountFeatures);
        $trackingCodeSnippet = $this->getresponseApiClient->getTrackingCodeSnippet();

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