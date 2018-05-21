<?php
namespace GrShareCode\Export\Settings;

/**
 * Class ExportSettingsFactory
 * @package GrShareCode\Export\Settings
 */
class ExportSettingsFactory
{
    /**
     * @param array $config
     * @return ExportSettings
     */
    public static function createFromArray($config)
    {
        return new ExportSettings(
            $config['contactListId'],
            $config['dayOfCycle'],
            $config['jobSchedulerEnabled'],
            $config['updateContactEnabled'],
            new EcommerceSettings(
                $config['ecommerceEnabled'],
                $config['shopId']
            )
        );
    }
}