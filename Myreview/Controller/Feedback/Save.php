<?php

namespace Feedback\Myreview\Controller\Feedback;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Feedback\Myreview\Model\FeedbackFactory;

class Save extends Action
{
    protected $feedbackFactory;

    public function __construct(
        Context $context,
        FeedbackFactory $feedbackFactory
    ) {
        parent::__construct($context);
        $this->feedbackFactory = $feedbackFactory;
    }

    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();

        if (!empty($postData)) {
            try {
                $feedbackModel = $this->feedbackFactory->create();
                $feedbackModel->setData($postData);
                $feedbackModel->save();
                $this->messageManager->addSuccessMessage(__('Feedback submitted successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Failed to submit feedback.'));
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }
}
