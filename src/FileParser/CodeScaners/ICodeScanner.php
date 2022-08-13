<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

interface ICodeScanner
{
    const DIRECTIVE_IN_PROGRESS = 0;
    const DIRECTIVE_DONE = 1;
    const DIRECTIVE_ISSUE = 10;

    /**
     * Accepts token_get_all result item in loop to get info needed
     * Returns DIRECTIVE constants. Send DIE to command IFileParser
     * to stop sending new tokens
     *
     * @param int $tokenCode
     * @param string $tokenName
     * @param string $tokenVal
     * @param int $fileStrNumber
     * @return integer
     */
    public function passToken(int $tokenCode, string $tokenName, string $tokenVal, int $fileStrNumber);

    /**
     * Stores parse results to dto
     *
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult);
}