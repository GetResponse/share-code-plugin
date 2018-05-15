<?php
namespace GrShareCode\Campaign;

use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;

/**
 * Class CampaignService
 * @package GrShareCode\Campaign
 */
class CampaignService
{
    const PER_PAGE = 100;

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
     * @return CampaignsCollection
     * @throws GetresponseApiException
     */
    public function getAllCampaigns()
    {
        $campaigns = $this->getresponseApi->getCampaigns(1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $campaigns = array_merge($campaigns,  $this->getresponseApi->getCampaigns($page, self::PER_PAGE));
        }

        $collection = new CampaignsCollection();

        foreach ($campaigns as $field) {
            $collection[] = new Campaign(
                $field['campaignId'],
                $field['name']
            );
        }

        return $collection;
    }

    /**√
     * @param string $campaignId
     * @return AutorespondersCollection
     * @throws GetresponseApiException
     */
    public function getCampaignAutoresponders($campaignId)
    {
        $collection = new AutorespondersCollection();

        $autoresponders = $this->getresponseApi->getAutoresponders($campaignId, 1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $autoresponders = array_merge($autoresponders,  $this->getresponseApi->getAutoresponders($campaignId, $page, self::PER_PAGE));
        }

        foreach ($autoresponders as $field) {
            $collection->add(new Autoresponder(
                $field['autoresponderId'],
                $field['name'],
                $field['campaignId'],
                $field['subject'],
                $field['status'],
                $field['triggerSettings']['dayOfCycle']
            ));
        }

        return $collection;
    }
}
