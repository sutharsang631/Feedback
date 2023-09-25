<?php
namespace Custom\ConfigViewer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Resource Model for Custom ConfigViewer Data
 *
 * This class represents the resource model for Custom ConfigViewer Data.
 */
class ConfigData extends AbstractDb
{
/**
 * This class represents the resource model for Custom ConfigViewer Data.
 */
    protected function _construct()
    {
        $this->_init('custom_configviewer_data', 'entity_id');
    }
}
