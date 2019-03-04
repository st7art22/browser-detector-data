<?php
/**
 * @author Serhii Nekhaienko <sergey.nekhaenko@gmail.com>
 * @license GPL
 * @copyright Serhii Nekhaienko &copy 2018-2019
 * @version 4.0.2
 * @project endorphin-studio/browser-detector
 */

namespace EndorphinStudio\Detector\Storage;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlStorage
 * Provide data files reader from yaml files
 * Use symfony\yaml component
 * @package EndorphinStudio\Detector\Storage
 */
class YamlStorage extends FileStorage implements StorageInterface
{
    /**
     * Get array of Data (patterns and etc.)
     * @return array array of data
     */
    public function getConfig(): array
    {
        $useCache = $this->isCacheEnabled();

        if($useCache && $this->checkCache()) {
            return $this->getFromCache();
        }

        if (empty($this->config)) {
            $yamlParser = new Parser();
            $files = $this->getFileNames();
            $this->config = [];
            foreach ($files as $file) {
                $this->config = \array_merge_recursive($this->config, $yamlParser->parseFile($file));
            }
        }

        if($useCache && !$this->checkCache()) {
            $this->saveCache($this->config);
        }

        return $this->config;
    }
}