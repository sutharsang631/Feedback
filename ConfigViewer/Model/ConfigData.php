<?php
namespace Custom\ConfigViewer\Model;

use Magento\Framework\Model\AbstractModel;
use Custom\ConfigViewer\Model\ResourceModel\ConfigData as ConfigDataResource;

/**
 * Model for Custom ConfigViewer Data
 *
 * This class represents the model for Custom ConfigViewer Data.
 */
class ConfigData extends AbstractModel
{
    /**
     * Model for Custom ConfigViewer Data
     *
     * This class represents the model for Custom ConfigViewer Data.
     */
    protected function _construct()
    {
        $this->_init(ConfigDataResource::class);
    }
}
