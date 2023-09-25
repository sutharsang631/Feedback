<?php
namespace Custom\ConfigViewer\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class CleanTable
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceConnection $resourceConnection
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResourceConnection $resourceConnection,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
    }

    /**
     * Clean the custom_configviewer_data table
     */
    public function execute()
    {
        try {
            // Get the cron schedule from the configuration
            $cronSchedule = $this->scopeConfig->getValue('configviewer/cron/schedule');

            // Use $cronSchedule as needed for your cron job logic
            // For example, you can check if it's time to run the job based on the schedule

            // If you want to run the job every hour, you can compare $cronSchedule with the current time
            $currentTime = strtotime('now');
            $scheduledTime = strtotime($cronSchedule);

            if ($scheduledTime <= $currentTime) {
                // Run your job logic here

                $connection = $this->resourceConnection->getConnection();
                $tableName = $connection->getTableName('custom_configviewer_data');

                // Truncate the table
                $connection->truncateTable($tableName);
            }
        } catch (\Exception $e) {
            // Handle any exceptions or log errors
            $this->logger->error($e->getMessage());
        }
    }
}
