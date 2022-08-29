<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\IScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class ClassNameScannerTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassScanner
 */
class ClassScannerTest extends BaseTestCase
{
    /**
     * @covers ::passToken
     * @covers ::storeScanResults
     */
    public function testScan()
    {
        $scanner = new ClassScanner();

        $codeFile = TEST_DIR . '/ExampleComponents/A/Classes/Class1.php';

        $this->assertTrue(is_file($codeFile));

        $code = file_get_contents($codeFile);
        $this->assertNotEmpty($code);

        $tokens = token_get_all($code);
        $this->assertNotEmpty($tokens);

        foreach ($tokens as $tokenInfo) {
            if (is_array($tokenInfo)) {
                list($tokenCode, $tokenVal, $fileStrNumber) = $tokenInfo;
                $tokenName = token_name($tokenCode);

                $resultDirective = $scanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);
            } else {
                $scanner->passString($tokenInfo);
            }
        }

        $result = $scanner->storeScanResults(new FileParseResult());
        $a = 1;
        //todo: test scanner
    }
}