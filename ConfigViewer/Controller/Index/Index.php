<?php
namespace Custom\ConfigViewer\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Custom\ConfigViewer\Block\ConfigDump;

/**
 * Class Index
 *
 * This controller handles the display of configuration data.
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ConfigDump
     */
    protected $configDumpBlock;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ConfigDump $configDumpBlock
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ConfigDump $configDumpBlock
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->configDumpBlock = $configDumpBlock;
    }

    /**
     * Execute action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        // Call the saveConfigData method from the block
        $this->configDumpBlock->saveConfigData();

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Configuration Dump'));
        return $resultPage;
    }
}
