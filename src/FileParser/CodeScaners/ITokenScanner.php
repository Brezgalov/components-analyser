<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

interface ITokenScanner extends IScanner
{
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
}