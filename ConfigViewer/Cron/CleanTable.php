<?php

namespace Custom\ConfigViewer\Cron;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CleanTable
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param ResourceConnection $resourceConnection
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ResourceConnection $resourceConnection, ScopeConfigInterface $scopeConfig)
    {
        $this->resourceConnection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Clean the custom_configviewer_data table
     */
    public function execute()
    {
        // Get the cron schedule from configuration
        $cronSchedule = $this->scopeConfig->getValue('custom_configviewer/cron_schedule');

        // Now, use $cronSchedule as your cron expression
        // ...

        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName('custom_configviewer_data');

        // Truncate the table
        $connection->truncateTable($tableName);
    }
}
