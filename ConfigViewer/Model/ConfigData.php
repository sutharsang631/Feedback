<?php
namespace Custom\ConfigViewer\Model;

use Magento\Framework\Model\AbstractModel;
use Custom\ConfigViewer\Model\ResourceModel\ConfigData as ConfigDataResource;

class ConfigData extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ConfigDataResource::class);
    }
}
