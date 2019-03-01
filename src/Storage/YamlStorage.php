<?php
/**
 * @author Serhii Nekhaienko <sergey.nekhaenko@gmail.com>
 * @license GPL
 * @copyright Serhii Nekhaienko &copy 2018
 * @version 4.0.0
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
        if (empty($this->config)) {
            $yamlParser = new Parser();
            $files = $this->getFileNames();
            $this->config = [];
            foreach ($files as $file) {
                $this->resolveData($yamlParser->parseFile($file));
            }
        }
        return $this->config;
    }

    /**
     * Resolve data
     * @param $data
     * @param string $container
     * @return array
     */
    public function resolveData($data, &$container = 'config'): array
    {
        $isRoot = false;
        if(is_string($container)) {
            $isRoot = true;
            $container = $this->config;
        }

        foreach ($data as $key => $item) {
            if(!\array_key_exists($key, $container)) {
                $container[$key] = [];
            }
            if(\is_array($item)) {
                $item = \array_merge($container[$key], $this->resolveData($item, $container[$key]));
            }
            $container[$key] = $item;
        }
        if($isRoot) {
            $this->config = $container;
        }
        return $container;
    }
}