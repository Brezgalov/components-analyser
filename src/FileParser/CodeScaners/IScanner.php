<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

interface IScanner
{
    const TOKEN_CLASS = "T_CLASS";
    const TOKEN_STRING = "T_STRING";
    const TOKEN_NAME_QUALIFIED = "T_NAME_QUALIFIED";
    const TOKEN_USE = "T_USE";
    const TOKEN_AS = "T_AS";
    const TOKEN_NS_SEPARATOR = "T_NS_SEPARATOR";
    const TOKEN_EXTENDS = "T_EXTENDS";
    const TOKEN_IMPLEMENTS = "T_IMPLEMENTS";

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