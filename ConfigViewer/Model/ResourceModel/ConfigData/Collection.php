<?php
namespace Custom\ConfigViewer\Model\ResourceModel\ConfigData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Custom\ConfigViewer\Model\ConfigData as ConfigDataModel;
use Custom\ConfigViewer\Model\ResourceModel\ConfigData as ConfigDataResourceModel;

/**
 * Custom ConfigViewer ConfigData Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Database primary key field name
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Initialize collection
     */
    protected function _construct()
    {
        $this->_init(
            ConfigDataModel::class,
            ConfigDataResourceModel::class
        );
    }
}
