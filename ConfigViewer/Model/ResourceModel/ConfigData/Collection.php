<?php
namespace Custom\ConfigViewer\Model\ResourceModel\ConfigData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Custom\ConfigViewer\Model\ConfigData as ConfigDataModel;
use Custom\ConfigViewer\Model\ResourceModel\ConfigData as ConfigDataResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            ConfigDataModel::class,
            ConfigDataResourceModel::class
        );
    }
}
