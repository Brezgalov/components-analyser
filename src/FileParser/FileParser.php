<?php

namespace Brezgalov\ComponentsAnalyser\FileParser;

abstract class FileParser implements IFileParser
{
    /**
     * @param string $filePath
     * @return array|false
     */
    protected function tokenizeFile(string $filePath)
    {
        $file = @file_get_contents($filePath);
        if ($file === false) {
            return false;
        }

        if (empty($file)) {
            return [];
        }

        return token_get_all($file);
    }
}