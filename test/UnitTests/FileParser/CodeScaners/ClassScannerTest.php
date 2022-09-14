<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassNameScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ClassScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ExtendsScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ImplementsScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\NamespaceScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\UseScanner;
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
     * @covers ::__construct
     */
    public function testContruct()
    {
        $scanner = new ClassScanner();

        $this->assertInstanceOf(ExtendsScanner::class, $scanner->extendsScanner);
        $this->assertInstanceOf(ImplementsScanner::class, $scanner->implementsScanner);
        $this->assertInstanceOf(UseScanner::class, $scanner->usesScanner);
        $this->assertInstanceOf(ClassNameScanner::class, $scanner->classNameScanner);
        $this->assertInstanceOf(NamespaceScanner::class, $scanner->namespaceScanner);
    }

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
        $this->assertCount(1, $result->getUseDependencies());
        $this->assertEquals('ExampleComponents\A\Classes\Base\BaseClass1', $result->getUseDependencies()[0]);
    }

    /**
     * @covers ::passToken
     * @covers ::passString
     * @covers ::storeScanResults
     */
    public function testLocalAndAliasScan2()
    {
        $tokens = $this->getTokens(TEST_DIR . '/ExampleComponents/A/Classes/Base/BaseClass1.php');

        $scanner = $this->scanTokens($tokens, new ClassScanner());

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('BaseClass1', $result->getClassName());
        $this->assertEquals('ExampleComponents\A\Classes\Base', $result->getNamespace());
        $this->assertEquals('ExampleComponents\A\Classes\Base\BaseClass1', $result->getFullClassName());
        $this->assertEquals(null, $result->getExtends());
        $this->assertEquals('ExampleComponents\A\Classes\Interface1', $result->getImplements());
        $this->assertCount(0, $result->getUseDependencies());
    }
}