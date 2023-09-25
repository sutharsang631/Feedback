<?php
namespace Custom\ConfigViewer\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Model for Custom Configuration Settings
 */
class Config
{
    /**
     * Configuration path for custom cron enabled flag
     */
    private const XML_PATH_CRON_ENABLED = 'custom_configviewer/cron/enabled';

    /**
     * Configuration path for custom cron schedule
     */
    private const XML_PATH_CRON_SCHEDULE = 'custom_configviewer/cron/schedule';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if the custom cron is enabled.
     *
     * @return bool
     */
    public function isCronEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_CRON_ENABLED);
    }

    /**
     * Get the custom cron schedule.
     *
     * @return string
     */
    public function getCronSchedule(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_CRON_SCHEDULE);
    }
}
