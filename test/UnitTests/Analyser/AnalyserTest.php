<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\Analyser;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\ComponentsAnalyser;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisDataPhpRepository;
use Brezgalov\ComponentsAnalyser\ComponentsPickerSimple\ComponentsPickerSimple;
use Brezgalov\ComponentsAnalyser\FileParserPhp8\FileParserPhp8;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class AnalyserTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\Analyser
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\ComponentsAnalyser\ComponentsAnalyser
 */
class AnalyserTest extends BaseTestCase
{
    /**
     * @throws \Exception
     *
     * @covers ::__construct
     * @covers ::addScanDirs
     * @covers ::addScanDir
     * @covers ::getScannedDirectories
     */
    public function testScanDirectories()
    {
        $picker = new ComponentsPickerSimple();

        $anyPossibleParser = new FileParserPhp8();

        $analyser = new ComponentsAnalyser($picker, $anyPossibleParser);
        $this->assertEquals([], $analyser->getScannedDirectories());

        $analyser = new ComponentsAnalyser($picker, $anyPossibleParser, __DIR__);
        $this->assertEquals([__DIR__], $analyser->getScannedDirectories());

        $analyser = new ComponentsAnalyser($picker, $anyPossibleParser, [__DIR__, __DIR__]);
        $this->assertEquals([__DIR__], $analyser->getScannedDirectories());

        $analyser = new ComponentsAnalyser($picker, $anyPossibleParser);
        $analyser->addScanDir(__DIR__);
        $analyser->addScanDirs([
            __DIR__ . '/../',
            __DIR__ . '/../../',
        ]);

        $this->assertEquals([
            __DIR__,
            __DIR__ . '/../',
            __DIR__ . '/../../',
        ], $analyser->getScannedDirectories());

        // exceptions check

        $directoryMissing = __DIR__ . '/' . uniqid();

        try {
            $analyser = new ComponentsAnalyser($picker, $anyPossibleParser, $directoryMissing);
        } catch (\Exception $ex1) {

        }

        $this->assertNotEmpty($ex1);

        $analyser = new ComponentsAnalyser($picker, $anyPossibleParser);
        try {
            $analyser->addScanDir($directoryMissing);
        } catch (\Exception $ex2) {

        }

        $this->assertNotEmpty($ex2);

        try {
            $analyser = new ComponentsAnalyser($picker, $anyPossibleParser, true);
        } catch (\Exception $ex3) {

        }

        $this->assertNotEmpty($ex3);
    }

    /**
     * @requires PHP >= 8.0
     *
     * @covers ::scanComponents
     */
    public function testAnalysisPhp8()
    {
        $picker = new ComponentsPickerSimple();
        $parser = new FileParserPhp8();

        $analyser = new ComponentsAnalyser($picker, $parser, TEST_DIR . '/ExampleComponentsNested');

        $result = $analyser->scanComponents();

        $this->assertInstanceOf(AnalysisDataPhpRepository::class, $result);
        $this->assertEquals(1, 0);
    }
}