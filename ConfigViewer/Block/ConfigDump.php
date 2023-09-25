<?php
namespace Custom\ConfigViewer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;
use Custom\ConfigViewer\Model\ResourceModel\ConfigData as ConfigDataResource;
use Magento\Cron\Model\Config\Reader\Xml;
use Magento\Cron\Model\Schedule;
use Custom\ConfigViewer\Model\ConfigData;

/**
 * Class ConfigDump
 *
 * This block class represents the configuration dump block.
 */
class ConfigDump extends Template
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var ConfigDataResource
     */
    protected $configDataResource;

    /**
     * @var Xml
     */
    protected $xmlReader;

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
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param ConfigDataResource $configDataResource
     * @param Xml $xmlReader
     * @param Schedule $schedule
     * @param array $data
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        ConfigDataResource $configDataResource,
        Xml $xmlReader,
        Schedule $schedule,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->objectManager = $objectManager;
        $this->configDataResource = $configDataResource;
        $this->xmlReader = $xmlReader;
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
        $configDataModel = $this->objectManager->create(\Custom\ConfigViewer\Model\ConfigData::class);

        // Flatten the configuration array and combine it into a single array
        $flattenedConfig = $this->flattenConfigArray($configArray);

        // Initialize an array to store the merged values
        $mergedConfig = [];

        // Merge the flattened arrays into a single array
        foreach ($flattenedConfig as $data) {
            $parentKey = $data['parent_key'];
            $key = $data['key'];
            $value = $data['value'];

            if (!isset($mergedConfig[$parentKey])) {
                $mergedConfig[$parentKey] = [];
            }

            $mergedConfig[$parentKey][$key] = $value;
        }

        // Iterate through the merged config and save the data
        foreach ($mergedConfig as $parentKey => $data) {
            foreach ($data as $key => $value) {
                $existingData = $this->getConfigDataByKeys($parentKey, $key);

                if (!$existingData) {
                    $configDataModel->setData([
                        'parent_key' => $parentKey,
                        'key' => $key,
                        'value' => $value,
                    ]);
                    $configDataModel->save();
                }
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
        $configDataModel = $this->objectManager->create(ConfigData::class);
        $configData = $configDataModel->getCollection()
            ->addFieldToFilter('parent_key', $parentKey)
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
                if (strpos($newKey, 'module') !== 0) { // Skip rows with 'module' in parent key
                    $result += $this->flattenConfigArray($value, $newKey, $separator);
                }
            } else {
                // Extract the initial key (the first part before $separator)
                $initialKey = explode($separator, $newKey)[0];

                // Skip rows with 'module' in parent key
                if ($initialKey !== 'module') {
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
        $jobCode = 'custom_configviewer_clean_table'; // Replace with your job code
        $lastSchedule = $this->schedule->getCollection()
            ->addFieldToFilter('job_code', $jobCode)
            ->setOrder('executed_at', 'DESC')
            ->setPageSize(1)
            ->getFirstItem();

        return $lastSchedule->getExecutedAt();
    }
}
