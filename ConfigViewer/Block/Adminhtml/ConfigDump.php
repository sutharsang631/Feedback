<?php

namespace Custom\ConfigViewer\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;
use Custom\ConfigViewer\Model\ResourceModel\ConfigData as ConfigDataResource;
use Magento\Cron\Model\Config\Reader\Xml;
use Magento\Cron\Model\Schedule;

/**
 * Block class for displaying configuration dump in the adminhtml.
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
     * ConfigDump constructor.
     *
     * @param Template\Context $context
     * @param ObjectManagerInterface $objectManager
     * @param ConfigDataResource $configDataResource
     * @param Xml $xmlReader
     * @param Schedule $schedule
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
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
    }

    /**
     * Get the configuration dump.
     *
     * @return array
     */
    public function getConfigDump()
    {
        // Read the configuration dump from config.php
        $configDump = include BP . '/app/code/Custom/ConfigViewer/view/config.php';
        return $configDump;
    }

    /**
     * Get the configuration as an array.
     *
     * @return array
     */
    public function getConfigArray()
    {
        $configDump = $this->getConfigDump();
        return is_array($configDump) ? $configDump : [];
    }

    /**
     * Save configuration data.
     */
    public function saveConfigData()
    {
        $configArray = $this->getConfigArray();
        $configDataModel = $this->objectManager->create(\Custom\ConfigViewer\Model\ConfigData::class);

        foreach ($this->flattenConfigArray($configArray) as $data) {
            $existingData = $this->getConfigDataByKeys($data['parent_key'], $data['key']);

            if (!$existingData) {
                $configDataModel->setData([
                    'parent_key' => $data['parent_key'],
                    'key' => $data['key'],
                    'value' => $data['value'],
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
        $configDataModel = $this->objectManager->create(\Custom\ConfigViewer\Model\ConfigData::class);
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
     * Flatten the configuration array.
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
                $result = array_merge($result, $this->flattenConfigArray($value, $newKey, $separator));
            } else {
                // Extract the initial key (the first part before $separator)
                $initialKey = explode($separator, $newKey)[0];
                $result[] = [
                    'parent_key' => $initialKey,
                    'key' => $newKey,
                    'value' => $value,
                ];
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
