<?php
namespace GrShareCode\Job;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\Export\ExportContactCommand;
use GrShareCode\Export\ExportContactServiceFactory;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;

/**
 * Class RunCommand
 * @package GrShareCode\Job
 */
class RunCommand
{
    /** @var DbRepositoryInterface */
    private $dbRepository;

    /** @var GetresponseApi */
    private $api;

    /**
     * @param GetresponseApi $api
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApi $api, DbRepositoryInterface $dbRepository)
    {
        $this->dbRepository = $dbRepository;
        $this->api = $api;
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
                    $this->exportCustomer(unserialize($messageContent));
                    break;
                default:
                    throw new JobException(sprintf('Job name:%s not specified', $job->getName()));
                    break;
            }

            $this->dbRepository->deleteJob($job);
        }
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    private function exportCustomer(ExportContactCommand $exportContactCommand)
    {
        $exportService = ExportContactServiceFactory::create($this->api, $this->dbRepository);
        $exportService->exportContact($exportContactCommand);
    }
}