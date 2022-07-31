<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\Analyser;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\ComponentsAnalyser;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisResult;
use Brezgalov\ComponentsAnalyser\ComponentsPickerSimple\ComponentsPickerSimple;
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

        $analyser = new ComponentsAnalyser($picker);
        $this->assertEquals([], $analyser->getScannedDirectories());

        $analyser = new ComponentsAnalyser($picker, __DIR__);
        $this->assertEquals([__DIR__], $analyser->getScannedDirectories());

        $analyser = new ComponentsAnalyser($picker, [__DIR__, __DIR__]);
        $this->assertEquals([__DIR__], $analyser->getScannedDirectories());

        $analyser = new ComponentsAnalyser($picker);
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
            $analyser = new ComponentsAnalyser($picker, $directoryMissing);
        } catch (\Exception $ex1) {

        }

        $this->assertNotEmpty($ex1);

        $analyser = new ComponentsAnalyser($picker);
        try {
            $analyser->addScanDir($directoryMissing);
        } catch (\Exception $ex2) {

        }

        $this->assertNotEmpty($ex2);

        try {
            $analyser = new ComponentsAnalyser($picker, true);
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

        $analyser = new ComponentsAnalyser($picker, TEST_DIR . '/ExampleComponents');

        $result = $analyser->scanComponents();

        $this->assertInstanceOf(AnalysisResult::class, $result);
        $this->assertEquals(1, 0);
    }
}