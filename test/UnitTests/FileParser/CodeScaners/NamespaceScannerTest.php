<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\NamespaceScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

class NamespaceScannerTest extends BaseTestCase
{
    public function testScan()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php');

        $scanner = $this->scanTokens($tokens, new NamespaceScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('ExampleComponents\A\Classes', $result->getNamespace());
    }

    /**
     * @covers ::storeScanResults
     * @covers ::passToken
     */
    public function testEmptyScript()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/C/config.php');

        $scanner = $this->scanTokens($tokens, new NamespaceScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals(null, $result->getNamespace());
    }
}