<?php
namespace Custom\ConfigViewer\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Custom\ConfigViewer\Block\ConfigDump; // Add this line

class Index extends Action
{
    protected $resultPageFactory;
    protected $configDumpBlock; // Add this property

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ConfigDump $configDumpBlock // Inject the ConfigDump block
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->configDumpBlock = $configDumpBlock;
    }

    public function execute()
    {
        $this->configDumpBlock->saveConfigData(); // Call the saveConfigData method from the block
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Configuration Dump'));
        return $resultPage;
    }
}
