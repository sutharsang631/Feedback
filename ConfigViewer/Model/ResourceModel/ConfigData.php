<?php
namespace Custom\ConfigViewer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ConfigData extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('custom_configviewer_data', 'entity_id');
    }
}
