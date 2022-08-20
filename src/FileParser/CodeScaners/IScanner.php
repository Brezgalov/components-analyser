<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

interface IScanner
{
    const DIRECTIVE_IN_PROGRESS = 0;
    const DIRECTIVE_DONE = 1;
    const DIRECTIVE_ISSUE = 10;

    /**
     * Stores parse results to dto
     *
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult);
}