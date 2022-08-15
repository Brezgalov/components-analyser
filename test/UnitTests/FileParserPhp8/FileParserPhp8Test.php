<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParserPhp8;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;
use Brezgalov\ComponentsAnalyser\FileParserPhp8\FileParserPhp8;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class FileParserPhp8Test
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParserPhp8
 *
 * @requires PHP >= 8.0
 */
class FileParserPhp8Test extends BaseTestCase
{
    /**
     * @covers \Brezgalov\ComponentsAnalyser\FileParser\FileParser::parseFile
     * @covers \Brezgalov\ComponentsAnalyser\FileParserPhp8\FileParserPhp8::getCodeScanners
     */
    public function testParse()
    {
        $parser = new FileParserPhp8();

        $result = $parser->parseFile(TEST_DIR . '/ExampleComponents/A/CompB.php');

        $this->assertInstanceOf(IFileParseResult::class, $result);
        $this->assertNotEmpty($result->getError());

        $result = $parser->parseFile(TEST_DIR . '/ExampleComponents/B/empty.php');
        $this->assertInstanceOf(IFileParseResult::class, $result);
        $this->assertEmpty($result->getError());
        $this->assertEmpty($result->getUseDependencies());

        $result = $parser->parseFile(TEST_DIR . '/ExampleComponents/A/MyFolder/TriggerC.php');
        $this->assertInstanceOf(IFileParseResult::class, $result);
        $this->assertEmpty($result->getError());

        $useDepends = $result->getUseDependencies();

        $this->assertTrue(in_array("ExampleComponents\C\CompC", $useDepends));
        $this->assertTrue(in_array("ExampleComponents\B\CompB", $useDepends));

        $this->assertEquals("ExampleComponents\A\MyFolder", $result->getNamespace());
        $this->assertEquals("TriggerC", $result->getClassName());
        $this->assertEquals("ExampleComponents\A\MyFolder\TriggerC", $result->getFullClassName());
    }
}