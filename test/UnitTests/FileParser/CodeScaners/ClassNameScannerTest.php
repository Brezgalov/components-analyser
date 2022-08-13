<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassNameScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ICodeScanner;
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
     * @covers ::passToken
     * @covers ::storeScanResults
     */
    public function testScan()
    {
        $scanner = new ClassNameScanner();

        $this->assertEquals(ICodeScanner::DIRECTIVE_IN_PROGRESS, $scanner->passToken(0, '', '', 0));

        $this->assertEquals(ICodeScanner::DIRECTIVE_IN_PROGRESS, $scanner->passToken(0, ClassNameScanner::TOKEN_CLASS, '', 0));

        $this->assertEquals(ICodeScanner::DIRECTIVE_IN_PROGRESS, $scanner->passToken(0, ClassNameScanner::TOKEN_CLASS, '', 0));

        $this->assertEquals(ICodeScanner::DIRECTIVE_IN_PROGRESS, $scanner->passToken(0, '', '', 0));

        $this->assertEquals(ICodeScanner::DIRECTIVE_DONE, $scanner->passToken(0, ClassNameScanner::TOKEN_STRING, 'myTestClass', 0));

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('myTestClass', $result->getClassName());
    }
}