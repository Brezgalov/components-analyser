<?php

namespace Brezgalov\ComponentsAnalyser\FileParserPhp7;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ITokenScanner;
use Brezgalov\ComponentsAnalyser\FileParser\FileParser;

class FileParserPhp7 extends FileParser
{
    /**
     * @return ITokenScanner[]
     */
    public function getCodeScanners()
    {
        return [
            new ClassScanner(),
        ];
    }
}