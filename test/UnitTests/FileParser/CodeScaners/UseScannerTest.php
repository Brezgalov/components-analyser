<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\UseScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class UseScannerTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\UseScanner
 */
class UseScannerTest extends BaseTestCase
{
    /**
     * @covers ::passToken
     * @covers ::passString
     * @covers ::storeScanResults
     */
    public function testUsagesWithAliasLocation()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php');

        $scanner = $this->scanTokens($tokens, new UseScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $usages = $result->getUseDependencies();
        $this->assertCount(1, $usages);
        $this->assertEquals('ExampleComponents\A\Classes\Base\BaseClass1', $usages[0]);
    }

    /**
     * @covers ::passToken
     * @covers ::passString
     * @covers ::storeScanResults
     */
    public function testUsagesNoAlias()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/A/CompA.php');

        $scanner = $this->scanTokens($tokens, new UseScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $usages = $result->getUseDependencies();
        $this->assertCount(1, $usages);
        $this->assertEquals('ExampleComponents\B\CompB', $usages[0]);
    }
}