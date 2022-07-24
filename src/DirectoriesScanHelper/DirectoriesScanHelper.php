<?php

namespace Brezgalov\ComponentsAnalyser\DirectoriesScanHelper;

class DirectoriesScanHelper
{
    /**
     * Finds directory nested folders names except '.' and '..'
     *
     * @param string $dir
     * @return array|false
     */
    public function scanDirContents(string $dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        $contents = scandir($dir);

        unset($contents[0], $contents[1]);

        return array_values($contents);
    }

    /**
     * Gets directory files list recursively
     *
     * @param string $dir - directory to scan
     * @param bool $fullPath - result as absolute paths list or local paths
     * @param int $maxDeep - max nested folders scanned (to prevent looping)
     * @param bool $dieOnMaxDeep - throw an exception on max deep overflow
     * @return array|false
     * @throws MaxDeepOverflowException
     */
    public function getDirectoryFiles(string $dir, $fullPath = true, $maxDeep = 100, $dieOnMaxDeep = true, $filePattern = '.php$')
    {
        $directoryContents = $this->scanDirContents($dir);
        if (!$directoryContents) {
            return false;
        }

        $files = [];

        $deep = 0;
        while ($directoryContents) {
            $item = array_shift($directoryContents);
            $itemPath = "{$dir}/{$item}";

            if (is_file($itemPath)) {
                if ($filePattern && !preg_match("/{$filePattern}/", $itemPath)) {
                    continue;
                }
                
                $files[] = $fullPath ? $itemPath : $item;
            } elseif (is_dir($itemPath)) {
                if ($deep >= $maxDeep) {
                    if ($dieOnMaxDeep) {
                        throw new MaxDeepOverflowException("Too many nested levels (more than {$maxDeep}). Possible directory loop detected");
                    } else {
                        continue;
                    }
                }

                $subFolderContents = $this->scanDirContents($itemPath);

                foreach ($subFolderContents as $subFolderItem) {
                    $directoryContents[] = "{$item}/{$subFolderItem}";
                }

                $deep += 1;
            }
        }

        return $files;
    }
}