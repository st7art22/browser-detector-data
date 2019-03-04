<?php
/**
 * @author Serhii Nekhaienko <sergey.nekhaenko@gmail.com>
 * @license GPL
 * @copyright Serhii Nekhaienko &copy 2018
 * @version 4.0.0
 * @project endorphin-studio/browser-detector
 */

namespace EndorphinStudio\Detector\Storage;

/**
 * Interface StorageInterface
 * Interface for abstract Storage Provider
 * @package EndorphinStudio\Detector\Storage
 */
interface StorageInterface
{
    /**
     * @param string $directory Set Data directory for loading
     * @return void
     */
    public function setDataDirectory(string $directory);

    /**
     * Get array of Data
     * @return array array of data
     */
    public function getConfig(): array;

    /**
     * Return true if cache enabled
     * @return bool return true if cache enabled
     */
    public function isCacheEnabled(): bool;

    /**
     * Set cache enabled flag
     * @param bool $flag true or false
     * @return void
     */
    public function setCacheEnabled(bool $flag);

    /**
     * Return path to cache directory
     * @return string Path to cache directory
     */
    public function getCacheDir(): string;

    /**
     * Set cache dir path
     * @param string $path Path to cache dir
     * @return void
     */
    public function setCacheDir(string $path);
}