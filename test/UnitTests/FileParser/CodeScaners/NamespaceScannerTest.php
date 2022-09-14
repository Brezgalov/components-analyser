<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\NamespaceScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class NamespaceScannerTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\NamespaceScanner
 */
class NamespaceScannerTest extends BaseTestCase
{
    /**
     * @covers ::passToken
     * @covers ::passString
     * @covers ::storeScanResults
     */
    public function testScan()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php');

        $scanner = $this->scanTokens($tokens, new NamespaceScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('ExampleComponents\A\Classes', $result->getNamespace());
    }

    /**
     * @covers ::passToken
     * @covers ::passString
     * @covers ::storeScanResults
     */
    public function testEmptyScript()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/C/config.php');

        $scanner = $this->scanTokens($tokens, new NamespaceScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals(null, $result->getNamespace());
    }
}