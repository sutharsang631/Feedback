<?php
namespace Feedback\Myreview\Controller\Adminhtml\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
class View extends Action
{
    protected $resultPageFactory = false;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
//        $resultPage->setActiveMenu('CustomFeedBack_FeedBackModule::FeedBack');
        $resultPage->getConfig()->getTitle()->prepend((__('Customer Feedbacks comments')));
        return $resultPage;
    }
}
