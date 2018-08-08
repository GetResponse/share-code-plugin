<?php
namespace GrShareCode\Export;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Job\JobFactory;

/**
 * Class ExportContact
 * @package GrShareCode\Export
 */
class ExportContact
{
    /** @var GetresponseApi */
    private $api;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /**
     * @param GetresponseApi $api
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApi $api, DbRepositoryInterface $dbRepository)
    {
        $this->api = $api;
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

        $exportService = ExportContactServiceFactory::create($this->api, $this->dbRepository);
        $exportService->exportContact($exportContactCommand);
    }
}