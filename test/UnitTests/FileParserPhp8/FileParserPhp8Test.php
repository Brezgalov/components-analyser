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
     * @covers \Brezgalov\ComponentsAnalyser\FileParser\FileParser::tokenizeFile
     * @covers \Brezgalov\ComponentsAnalyser\FileParserPhp8\FileParserPhp8::parseFile
     */
    public function testParse()
    {
        $parser = new FileParserPhp8();

        $result = $parser->parseFile(TEST_DIR . '/ExampleComponentsCircled/A/CompB.php');

        $this->assertInstanceOf(IFileParseResult::class, $result);
        $this->assertNotEmpty($result->getError());

        $result = $parser->parseFile(TEST_DIR . '/ExampleComponentsCircled/B/empty.php');
        $this->assertInstanceOf(IFileParseResult::class, $result);
        $this->assertEmpty($result->getError());
        $this->assertEmpty($result->getUseDependencies());

        $result = $parser->parseFile(TEST_DIR . '/ExampleComponentsCircled/A/MyFolder/TriggerC.php');
        $this->assertInstanceOf(IFileParseResult::class, $result);
        $this->assertEmpty($result->getError());

        $useDepends = $result->getUseDependencies();

        $this->assertTrue(in_array("ExampleComponentsCircled\C\CompC", $useDepends));
        $this->assertTrue(in_array("ExampleComponentsCircled\B\CompB", $useDepends));

        $this->assertEquals("ExampleComponentsCircled\A\MyFolder", $result->namespace);
        $this->assertEquals("TriggerC", $result->className);
        $this->assertEquals("ExampleComponentsCircled\A\MyFolder\TriggerC", $result->getFullClassName());
    }
}