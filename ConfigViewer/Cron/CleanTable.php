<?php
namespace Custom\ConfigViewer\Cron;

use Magento\Framework\App\ResourceConnection;

class CleanTable
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * Constructor
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Clean the custom_configviewer_data table
     */
    public function execute()
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName('custom_configviewer_data');
        
        // Truncate the table
        $connection->truncateTable($tableName);
    }
}
