<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassScanner;
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
     * @covers ::passString
     * @covers ::storeScanResults
     */
    public function testLocalAndAliasScan()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php');

        $scanner = $this->scanTokens($tokens, new ClassScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('Class1', $result->getClassName());
        $this->assertEquals('ExampleComponents\A\Classes', $result->getNamespace());
        $this->assertEquals('ExampleComponents\A\Classes\Class1', $result->getFullClassName());
        $this->assertEquals('ExampleComponents\A\Classes\Base\BaseClass1', $result->getExtends());
        $this->assertEquals('ExampleComponents\A\Classes\Interface1', $result->getImplements());
    }
}