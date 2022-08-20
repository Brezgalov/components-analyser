<?php

namespace Brezgalov\ComponentsAnalyser\FileParser;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ITokenScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

interface IFileParser
{
    /**
     * @param string $filePath
     * @return IFileParseResult
     */
    public function parseFile(string $filePath);
}