<?php
/**
 * @author Serhii Nekhaienko <sergey.nekhaenko@gmail.com>
 * @license GPL
 * @copyright Serhii Nekhaienko &copy 2018-2019
 * @version 4.0.2
 * @project endorphin-studio/browser-detector
 */

namespace EndorphinStudio\Detector\Storage;

use EndorphinStudio\Detector\Tools;

/**
 * File Storage of data
 * Class FileStorage
 * @package EndorphinStudio\Detector\Storage
 */
class FileStorage extends AbstractStorage implements StorageInterface
{
    /**
     * Base method
     * @return array nothing
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function getCacheHash(string $directory = 'default'): string
    {
        $fileNames = $this->getFileNames($directory);
        $hash = '';
        foreach ($fileNames as $fileName) {
            $hash = sprintf('%s%s', $hash, md5_file($fileName));
        }
        return md5($hash);
    }

    public function checkCache(string $directory = 'default'): bool
    {
        return $this->getHashFromHashList($directory) === $this->getCacheHash($directory);
    }

    private function getHashFromHashList(string $directory = 'default'): string
    {
        $hashList = $this->getHashList();
        if (array_key_exists($directory, $hashList)) {
            return $hashList[$directory];
        }
        return '';
    }

    private function getHashList(): array
    {
        $fileName = $this->getCacheListFileName();
        if (file_exists($fileName)) {
            $file = new \SplFileObject($fileName);
            return unserialize($file->fread($file->getSize()));
        }
        return [];
    }

    public function saveCacheFileList(string $directory = 'default')
    {
        $key = $directory;
        if ($directory === 'default') {
            $key = $this->dataDirectory;
        }
        $hashList = $this->getHashList();
        $hashList[$key] = $this->getCacheHash($directory);
        $file = new \SplFileObject($this->getCacheListFileName(), 'w+');
        $file->fwrite(serialize($hashList));
    }

    public function saveCache(array $content, string $directory = 'default')
    {
        $fileName = $this->getCacheFileName($directory);
        $file = new \SplFileObject($fileName, 'w+');
        $file->fwrite(serialize($content));
        $this->saveCacheFileList();
    }

    protected function getFromCache(string $directory = 'default'): array
    {
        $fileName = $this->getCacheFileName($directory);
        $file = new \SplFileObject($fileName, 'o');
        $data = $file->fread($file->getSize());
        return unserialize($data);
    }

    /**
     * Get list of paths in directory
     * @param string $directory
     * @return array
     */
    protected function getFileNames(string $directory = 'default'): array
    {
        $directoryIterator = $this->getDirectoryIterator($directory);
        $files = [];
        foreach ($directoryIterator as $file) {
            $this->resolveFile($file, $files);
        }
        return $files;
    }

    /**
     * Add file to list or scan directory
     * @param \DirectoryIterator $file
     * @param array $files
     */
    private function resolveFile(\DirectoryIterator $file, array &$files)
    {
        if ($file->isDir() && !$file->isDot()) {
            $files = Tools::resolvePath($files, $this->getFileNames($file->getRealPath()));
        }

        if ($file->isFile() && !$file->isLink() && $file->isReadable()) {
            $files = Tools::resolvePath($files, $file->getRealPath());
        }
    }

    /**
     * Get Directory Iterator
     * @param string $directory
     * @return \DirectoryIterator
     */
    private function getDirectoryIterator(string $directory): \DirectoryIterator
    {
        if ($directory === 'default') {
            return new \DirectoryIterator($this->dataDirectory);
        }
        return new \DirectoryIterator($directory);
    }

    private function getCacheListFileName(): string
    {
        return sprintf('%s/cache.bin', $this->getCacheDirectory());
    }

    private function getCacheFileName(string $directory): string
    {
        return sprintf('%s/%s.bin', $this->getCacheDirectory(), $this->getCacheHash($directory));
    }
}