<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser;

use Brezgalov\ComponentsAnalyser\FileParser\FileParser;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class FileParserTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParser
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\FileParser\FileParser
 */
class FileParserTest extends BaseTestCase
{
    /**
     * @covers ::getCodeScanners
     * @covers ::parseFile
     */
    public function testLocalAndAliasScan()
    {
        $parser = new FileParser();
        $result = $parser->parseFile(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php');

        $this->assertEquals('Class1', $result->getClassName());
        $this->assertEquals('ExampleComponents\A\Classes', $result->getNamespace());
        $this->assertEquals('ExampleComponents\A\Classes\Class1', $result->getFullClassName());
        $this->assertEquals('ExampleComponents\A\Classes\Base\BaseClass1', $result->getExtends());
        $this->assertEquals('ExampleComponents\A\Classes\Interface1', $result->getImplements());
        $this->assertCount(1, $result->getUseDependencies());
        $this->assertEquals('ExampleComponents\A\Classes\Base\BaseClass1', $result->getUseDependencies()[0]);
    }

    /**
     * @covers ::getCodeScanners
     * @covers ::parseFile
     */
    public function testLocalAndAliasScan2()
    {
        $parser = new FileParser();
        $result = $parser->parseFile(TEST_DIR . '/ExampleComponents/A/Classes/Base/BaseClass1.php');

        $this->assertEquals('BaseClass1', $result->getClassName());
        $this->assertEquals('ExampleComponents\A\Classes\Base', $result->getNamespace());
        $this->assertEquals('ExampleComponents\A\Classes\Base\BaseClass1', $result->getFullClassName());
        $this->assertEquals(null, $result->getExtends());
        $this->assertEquals('ExampleComponents\A\Classes\Interface1', $result->getImplements());
        $this->assertCount(0, $result->getUseDependencies());
    }
}