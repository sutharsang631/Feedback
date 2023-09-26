<?php
namespace Custom\ConfigViewer\App\Config;

use Magento\Framework\App\Config\Initial as Initial1;

class Initial extends Initial1
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
