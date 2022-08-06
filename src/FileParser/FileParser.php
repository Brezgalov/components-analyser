<?php

namespace Brezgalov\ComponentsAnalyser\FileParser;

// @todo: teach parsers to understand "require" directive
// @todo: teach parsers to search for fully qualified names inside class body
// @todo: teach parsers to determine abstract classes
// @todo: teach parsers to determine interfaces
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