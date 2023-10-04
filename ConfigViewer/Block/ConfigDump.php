<?php
namespace Custom\ConfigViewer\Block;

use Magento\Framework\View\Element\Template;
use Custom\ConfigViewer\Model\ResourceModel\ConfigData\CollectionFactory;
use Magento\Cron\Model\Schedule;

/**
 * Class ConfigDump
 *
 * This block class represents the configuration dump block.
 */
class ConfigDump extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $configDataCollectionFactory;

    /**
     * @var Schedule
     */
    protected $schedule;

    /**
     * @var array
     */
    protected $configDump;

    /**
     * ConfigDump constructor.
     *
     * @param Template\Context $context
     * @param CollectionFactory $configDataCollectionFactory
     * @param Schedule $schedule
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $configDataCollectionFactory,
        Schedule $schedule,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configDataCollectionFactory = $configDataCollectionFactory;
        $this->schedule = $schedule;
        $this->configDump = $this->loadConfigDump();
    }

    /**
     * Load the configuration dump.
     *
     * @return array
     */
    protected function loadConfigDump()
    {
        // phpcs:disable
        return include BP . '/app/etc/config.php';
        // phpcs:enable
    }

    /**
     * Get the configuration dump.
     *
     * @return array
     */
    public function getConfigDump()
    {
        return $this->configDump;
    }

    /**
     * Get the configuration as an array.
     *
     * @return array
     */
    public function getConfigArray()
    {
        return is_array($this->configDump) ? $this->configDump : [];
    }

    /**
     * Save configuration data.
     */
    public function saveConfigData()
    {
        $configArray = $this->getConfigArray();
        $flattenedConfig = $this->flattenConfigArray($configArray);

        foreach ($flattenedConfig as $data) {
            $parentKey = $data['parent_key'];
            $key = $data['key'];
            $value = $data['value'];
            $existingData = $this->getConfigDataByKeys($parentKey, $key);

            if (!$existingData || !$existingData->getId()) {
                $configDataModel = $this->configDataCollectionFactory->create()->getNewEmptyItem();
                $configDataModel->setData([
                    'parent_key' => $parentKey,
                    'key' => $key,
                    'value' => $value,
                ]);
                $configDataModel->save();
            }
        }
    }

    /**
     * Get configuration data by keys.
     *
     * @param string $parentKey
     * @param string $key
     * @return mixed|null
     */
    private function getConfigDataByKeys($parentKey, $key)
    {
        $configDataCollection = $this->configDataCollectionFactory->create();
        $configData = $configDataCollection->addFieldToFilter('parent_key', $parentKey)
            ->addFieldToFilter('key', $key)
            ->getFirstItem();

        if ($configData->getId()) {
            return $configData;
        }

        return null;
    }

    /**
     * Flatten the configuration array, excluding rows with 'module' in the parent key.
     *
     * @param array $configArray
     * @param string $parentKey
     * @param string $separator
     * @return array
     */
    public function flattenConfigArray($configArray, $parentKey = '', $separator = ': ')
    {
        $result = [];
        foreach ($configArray as $key => $value) {
            $newKey = empty($parentKey) ? $key : $parentKey . $separator . $key;
            if (is_array($value)) {
                $result += $this->flattenConfigArray($value, $newKey, $separator);
            } else {
                $initialKey = explode($separator, $newKey)[0];
                if ($initialKey !== 'modules' && ($value == '1' || $value == '0')) {
                    $result[] = [
                        'parent_key' => $initialKey,
                        'key' => $newKey,
                        'value' => $value,
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * Get the last execution time of a custom cron job.
     *
     * @return string|null
     */
    public function getLastExecutionTime()
    {
        // Get the last execution time of your custom cron job
        $jobCode = 'custom_configviewer_clean_table';
        $connection = $this->configDataCollectionFactory->create()->getConnection();
        $select = $connection->select()
            ->from(['s' => $connection->getTableName('cron_schedule')], ['executed_at'])
            ->where('job_code = ?', $jobCode)
            ->order('executed_at DESC')
            ->limit(1);

        $lastExecutedAt = $connection->fetchOne($select);
        
        return $lastExecutedAt;
    }
}
