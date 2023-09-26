<?php
namespace Custom\ConfigViewer\App\Config;

class Initial extends \Magento\Framework\App\Config\Initial
{
    /**
     * Get default configuration data
     *
     * @return array
     */
    public function getDefaultData()
    {
        $configData = [];
        $modules = $this->moduleList->getNames();

        foreach ($modules as $module) {
            $configFile = $this->configReader->getModuleConfigFileName($module);
            if ($configFile) {
                $data = $this->configReader->read($module, $configFile);
                if (isset($data['default'])) {
                    $configData = $this->merger->merge($configData, $data['default']);
                }
            }
        }

        return $configData;
    }
}
