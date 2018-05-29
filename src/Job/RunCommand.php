<?php
namespace GrShareCode\Job;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\Export\ExportCustomer;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;

/**
 * Class RunCommand
 * @package GrShareCode\Job
 */
class RunCommand
{
    /** @var ExportCustomer */
    private $exportCustomer;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /**
     * @param GetresponseApi $api
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApi $api, DbRepositoryInterface $dbRepository)
    {
        $this->exportCustomer = new ExportCustomer($api, $dbRepository);
        $this->dbRepository = $dbRepository;
    }

    /**
     * @throws JobException
     * @throws GetresponseApiException
     */
    public function execute()
    {
        /** @var Job $job */
        foreach ($this->dbRepository->getJobsToProcess() as $job) {

            $messageContent = $job->getMessageContent();

            switch ($job->getName()) {
                case Job::NAME_EXPORT_CONTACT:
                    $this->exportCustomer->execute(unserialize($messageContent));
                    break;
                default:
                    throw new JobException(sprintf('Job name:%s not specified', $job->getName()));
                    break;
            }

            $this->dbRepository->deleteJob($job);
        }
    }
}