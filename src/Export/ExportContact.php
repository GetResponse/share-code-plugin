<?php
namespace GrShareCode\Export;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\Job\JobFactory;

/**
 * Class ExportContact
 * @package GrShareCode\Export
 */
class ExportContact
{
    /** @var GetresponseApiClient */
    private $getresponseApiClient;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository)
    {
        $this->getresponseApiClient = $getresponseApiClient;
        $this->dbRepository = $dbRepository;
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    public function execute(ExportContactCommand $exportContactCommand)
    {
        if ($exportContactCommand->getExportSettings()->isJobSchedulerEnabled()) {
            $this->dbRepository->addJob(JobFactory::createForContactExportCommand($exportContactCommand));
            return;
        }

        $exportService = ExportContactServiceFactory::create($this->getresponseApiClient, $this->dbRepository);
        $exportService->exportContact($exportContactCommand);
    }
}