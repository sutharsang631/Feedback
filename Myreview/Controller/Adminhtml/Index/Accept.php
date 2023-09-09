<?php
namespace Feedback\Myreview\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Feedback\Myreview\Helper\Mail;
use Magento\Framework\View\Result\PageFactory;
use Feedback\Myreview\Model\FeedbackFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Feedback\Myreview\Model\ResourceModel\Feedback;
use Magento\Backend\App\Action;

class Accept extends Action
{
    protected $resultPageFactory;
    protected $feedbackFactory;
    protected $jsonFactory;
    protected $feedbackResource;
    protected $_helperMail;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        FeedbackFactory $feedbackFactory,
        JsonFactory $jsonFactory,
        Feedback $feedbackResource,
        Mail $helperMail
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->feedbackFactory = $feedbackFactory;
        $this->jsonFactory = $jsonFactory;
        $this->feedbackResource = $feedbackResource; // Corrected property name
        $this->_helperMail = $helperMail;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $customername=$this->getRequest()->getParam('FirstName');
        // dd($customername);
        $data = [
            'status' => 'approved',
        ];

        try {
            $feedback = $this->feedbackFactory->create();
            $this->feedbackResource->load($feedback, $id);
            $feedback->addData($data);
            $this->feedbackResource->save($feedback);
            $this->_helperMail->sendEmail('sutharsan@gmail.com','email_accept_template');
            // $result = $this->jsonFactory->create()->setData(['success' => true]);
            $this->messageManager->addSuccessMessage(__("Feedback is Accepted"));
            $redirect = $this->resultRedirectFactory->create();
            $redirect->setPath('myreview1/index/index');
            return $redirect;
        } catch (\Exception $e) {
            $result = $this->jsonFactory->create()->setData(['error' => $e->getMessage()]);
        }

        return $result;
    }
}

