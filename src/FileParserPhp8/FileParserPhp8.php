<?php

namespace Brezgalov\ComponentsAnalyser\FileParserPhp8;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassScanner;
use Brezgalov\ComponentsAnalyser\FileParser\FileParser;

class FileParserPhp8 extends FileParser
{
    /**
     * @return array
     */
    public function getCodeScanners()
    {
        return [
            new ClassScanner(),
        ];
    }
}