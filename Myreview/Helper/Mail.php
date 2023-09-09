<?php

namespace Feedback\Myreview\Helper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
class Mail extends AbstractHelper
{
    protected $transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $state
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        parent::__construct($context);
    }

    public function sendEmail($email,$template)
    {
      
        $templateId = $template;
        $fromEmail = 'sales@example.com';
        $fromName = 'Admin';
        $toEmail = $email;
        try {
            $templateVars = [
               'accept'=>'accepted',
                'reject'=>'rejected'
            ];
            $storeId = $this->storeManager->getStore()->getId();
            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                'store' => $storeId
            ];
            $transport = $this->transportBuilder->setTemplateIdentifier($templateId,
                $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($toEmail)
                ->getTransport();
                // dd($templateVars);
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
        }
    }
}
