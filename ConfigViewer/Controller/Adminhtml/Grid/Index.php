<?php
namespace Custom\ConfigViewer\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Controller for managing the grid in the admin panel.
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute the action.
     */
    public function execute()
    {
        if (!$this->_authorization->isAllowed('Custom_ConfigViewer::configviewer_cron')) {
            $this->messageManager->addErrorMessage(__('You do not have permission to access this.'));
            return $this->_redirect('admin/dashboard/index');
        }

        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Custom_ConfigViewer::gridmanager');
        $resultPage->getConfig()->getTitle()->prepend(__('Grid List'));

        return $resultPage;
    }
}
