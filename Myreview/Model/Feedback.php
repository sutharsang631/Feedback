<?php

namespace Feedback\Myreview\Model;

use Magento\Framework\Model\AbstractModel;

class Feedback extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Feedback\Myreview\Model\ResourceModel\Feedback');
    }
}
