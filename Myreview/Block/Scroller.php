<?php

namespace Feedback\Myreview\Block;

use Magento\Framework\View\Element\Template;
use Feedback\Myreview\Model\ResourceModel\Feedback\CollectionFactory;

class Scroller extends Template
{
    protected $feedbackCollectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $feedbackCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->feedbackCollectionFactory = $feedbackCollectionFactory;
    }

    public function getApprovedFeedback()
    {
        $collection = $this->feedbackCollectionFactory->create();
        $collection->addFieldToFilter('status', 'approved');
        $collection->setPageSize(5); // Adjust the number of feedback items to display
        return $collection;
    }
}
