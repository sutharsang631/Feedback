<?php
namespace Custom\ConfigViewer\Cron;

use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class TruncateTable
{
    protected $resource;
    protected $logger;

    public function __construct(ResourceConnection $resource, LoggerInterface $logger)
    {
        $this->resource = $resource;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            $connection = $this->resource->getConnection();
            $tableName = $this->resource->getTableName('custom_configviewer_data');

            $connection->truncateTable($tableName);
            $this->logger->info('Custom_ConfigViewer table truncated successfully.');
        } catch (\Exception $e) {
            $this->logger->error('Error truncating Custom_ConfigViewer table: ' . $e->getMessage());
        }
    }
}
