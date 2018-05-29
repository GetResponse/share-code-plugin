<?php
namespace GrShareCode\Job;

use GrShareCode\Export\ExportContactCommand;

/**
 * Class JobFactory
 * @package GrShareCode\Job
 */
class JobFactory
{
    /**
     * @param ExportContactCommand $exportContactCommand
     * @return Job
     */
    public static function createForContactExportCommand(ExportContactCommand $exportContactCommand)
    {
        return new Job(Job::NAME_EXPORT_CONTACT, serialize($exportContactCommand));
    }
}