<?php

namespace Feedback\Myreview\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Feedback extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('customer_feedback', 'feedback_id');
    }
}
