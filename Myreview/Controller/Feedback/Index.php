<?php

namespace Feedback\Myreview\Controller\Feedback;

/**
 * Class View
 *
 * @package Example\HelloWorld\Controller\Page
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $pageFactory = $this->pageFactory->create();
        // Add page title
        $pageFactory ->getConfig()->getTitle()->set(__('Feedback Form'));

        return $this->pageFactory->create();
    }
}
