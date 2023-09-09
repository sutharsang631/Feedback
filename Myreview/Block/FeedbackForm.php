<?php

namespace Feedback\Myreview\Block;

// use Magento\Framework\View\Element\Template;
// use  Feedback\Feedback\Model\ResourceModel\Feedback\Collection;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\View\Element\Template;


class FeedbackForm extends Template
{

    // private $collection;

    /** @var SessionFactory */
    protected $session;

    /** @var */
    protected $customerData;

    /**
     * Feedback constructor.
     * @param Template\Context $context
     * @param SessionFactory $session
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        SessionFactory $session,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->session= $session->create();
        $this->customerData = ($this->session->isLoggedIn()) ? $this->session->getCustomerData() : null;
    }
    public function getCustomerFirstName()
    {
        if($this->customerData) 
             return $this->customerData->getFirstname();
        else 
            return false;
    }

    public function getCustomerLastName()
    {
        if($this->customerData) 
             return $this->customerData->getLastname();
        else 
            return false;
    }

    public function getCustomerEmail()
    {
        if($this->customerData) 
             return $this->customerData->getEmail();
        else 
            return false;
    }

    public function getActionUrl()
    {
        return $this->getUrl('myreview/feedback/save', ['_secure' => true]);
    }


    public function getFormAction()
    {
        return $this->getUrl('myreview/feedback/index');
    }
}

