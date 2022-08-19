<?php

namespace Brezgalov\ComponentsAnalyser\FileParserPhp8;

use Brezgalov\ComponentsAnalyser\FileParser\FileParser;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;

class FileParserPhp8 extends FileParser
{
    /**
     * @return array
     */
    public function getCodeScanners()
    {
        return [

        ];
    }
}