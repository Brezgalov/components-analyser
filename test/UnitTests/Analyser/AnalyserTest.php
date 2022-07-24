<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\Analyser;

use Brezgalov\ComponentsAnalyser\Analyser\Analyser;
use Brezgalov\ComponentsAnalyser\Analyser\Models\AnalysisResult;
use Brezgalov\ComponentsAnalyser\ComponentsPickerSimple\ComponentsPickerSimple;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class AnalyserTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\Analyser
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\Analyser\Analyser
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

        $analyser = new Analyser($picker);
        $this->assertEquals([], $analyser->getScannedDirectories());

        $analyser = new Analyser($picker, __DIR__);
        $this->assertEquals([__DIR__], $analyser->getScannedDirectories());

        $analyser = new Analyser($picker, [__DIR__, __DIR__]);
        $this->assertEquals([__DIR__], $analyser->getScannedDirectories());

        $analyser = new Analyser($picker);
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
            $analyser = new Analyser($picker, $directoryMissing);
        } catch (\Exception $ex1) {

        }

        $this->assertNotEmpty($ex1);

        $analyser = new Analyser($picker);
        try {
            $analyser->addScanDir($directoryMissing);
        } catch (\Exception $ex2) {

        }

        $this->assertNotEmpty($ex2);

        try {
            $analyser = new Analyser($picker, true);
        } catch (\Exception $ex3) {

        }

        $this->assertNotEmpty($ex3);
    }

    /**
     * @covers ::scanComponents
     */
    public function testAnalysis()
    {
        $picker = new ComponentsPickerSimple();

        $analyser = new Analyser($picker, TEST_DIR . '/ExampleComponents');

        $result = $analyser->scanComponents();

        $this->assertInstanceOf(AnalysisResult::class, $result);
        $this->assertEquals(1, 0);
    }
}