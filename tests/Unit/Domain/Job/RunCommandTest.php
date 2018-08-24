<?php
namespace GrShareCode\Tests\Unit\Domain\Job;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\Export\Settings\ExportSettingsFactory;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Job\Job;
use GrShareCode\Job\JobCollection;
use GrShareCode\Job\JobException;
use GrShareCode\Job\JobFactory;
use GrShareCode\Job\RunCommand;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

class RunCommandTest extends TestCase
{
    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dbRepositoryMock;

    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;

    public function setUp()
    {
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->grApiClientMock = $this->getMockBuilder(GetresponseApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldExportClient()
    {
        $exportSettings = ExportSettingsFactory::createFromArray([
            'contactListId' => 'contactListId',
            'dayOfCycle' => null,
            'jobSchedulerEnabled' => false,
            'updateContactEnabled' => false,
            'ecommerceEnabled' => false,
            'shopId' => null
        ]);

        $exportContactCommand = Generator::createExportContactCommandWithSettings($exportSettings);

        $job1 = JobFactory::createForContactExportCommand($exportContactCommand);
        $job2 = JobFactory::createForContactExportCommand($exportContactCommand);

        $jobCollection = new JobCollection();
        $jobCollection->add($job1);
        $jobCollection->add($job2);

        $this->dbRepositoryMock
            ->expects($this->exactly(2))
            ->method('deleteJob');

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getJobsToProcess')
            ->willReturn($jobCollection);

        $command = new RunCommand($this->grApiClientMock, $this->dbRepositoryMock);
        $command->execute();
    }

    /**
     * @test
     */
    public function shouldThrowExportOnInvalidJobName()
    {
        $this->expectException(JobException::class);

        $exportSettings = ExportSettingsFactory::createFromArray([
            'contactListId' => 'contactListId',
            'dayOfCycle' => null,
            'jobSchedulerEnabled' => false,
            'updateContactEnabled' => false,
            'ecommerceEnabled' => false,
            'shopId' => null
        ]);

        $exportContactCommand = Generator::createExportContactCommandWithSettings($exportSettings);

        $jobCollection = new JobCollection();
        $jobCollection->add(new Job('non_existing_name', serialize($exportContactCommand)));

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getJobsToProcess')
            ->willReturn($jobCollection);

        $command = new RunCommand($this->grApiClientMock, $this->dbRepositoryMock);
        $command->execute();
    }

}
