<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassNameScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class ClassNameScannerTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassNameScanner
 */
class ClassNameScannerTest extends BaseTestCase
{
    /**
     * @covers ::storeScanResults
     * @covers ::passToken
     */
    public function testScanClassName()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php');

        $scanner = $this->scanTokens($tokens, new ClassNameScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('Class1', $result->getClassName());
    }

    /**
     * @covers ::storeScanResults
     * @covers ::passToken
     */
    public function testEmptyScript()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/C/config.php');

        $scanner = $this->scanTokens($tokens, new ClassNameScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals(null, $result->getClassName());
    }
}