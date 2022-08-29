<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\IScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\IStringScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ITokenScanner;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * @param string $filePath
     * @return array
     */
    protected function getTokens(string $filePath)
    {
        $this->assertTrue(is_file($filePath));

        $code = file_get_contents($filePath);
        $this->assertNotEmpty($code);

        $tokens = token_get_all($code);
        $this->assertNotEmpty($tokens);

        return $tokens;
    }

    /**
     * @param array $tokens
     * @param IScanner $scanner
     * @return IScanner|IStringScanner|ITokenScanner
     */
    protected function scanTokens(array $tokens, IScanner $scanner)
    {
        foreach ($tokens as $tokenInfo) {
            if (is_array($tokenInfo) && $scanner instanceof ITokenScanner) {
                list($tokenCode, $tokenVal, $fileStrNumber) = $tokenInfo;
                $tokenName = token_name($tokenCode);

                $scanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);
            }

            if (is_string($tokenInfo) && $scanner instanceof IStringScanner) {
                $scanner->passString($tokenInfo);
            }
        }

        return $scanner;
    }
}