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
                $this->resolveData($yamlParser->parseFile($file), $this->config);
            }
        }
        return $this->config;
    }

    public function resolveData($data, &$array): array
    {
        array_walk($data, function ($item, $key) use ($array) {
            if(!\array_key_exists($key, $array)) {
                $array[$key] = [];
            }
            if(\is_array($item)) {
                $array[$key] = \array_merge($array[$key], $this->resolveData($item, $array));
            } else {
                $array[$key] = $item;
            }
        });
        return $array;
    }
}