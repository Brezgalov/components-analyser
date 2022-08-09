<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\Analyser;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\ComponentsAnalyser;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisDataPhpRepository;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\DirectoryAnalysisSettings;
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
     * @covers ::addDirectorySettings
     * @covers ::getDirectoriesSettings
     */
    public function testScanDirectories()
    {
        $anyPossibleParser = new FileParserPhp8();

        $analyser = new ComponentsAnalyser([], $anyPossibleParser);

        $this->assertEmpty($analyser->getDirectoriesSettings());

        $picker = new ComponentsPickerSimple();
        $settings1 = new DirectoryAnalysisSettings(__DIR__, $picker);

        $settings = [$settings1];
        $analyser = new ComponentsAnalyser($settings, $anyPossibleParser);

        $settingsGot = $analyser->getDirectoriesSettings();
        $this->assertCount(1, $settingsGot);
        $this->assertEquals($settings1, $settingsGot[0]);

        // same dir
        $settings2 = new DirectoryAnalysisSettings(__DIR__, $picker);

        $analyser->addDirectorySettings($settings2);

        $settingsGot = $analyser->getDirectoriesSettings();
        $this->assertCount(1, $settingsGot);
        $this->assertEquals($settings2, $settingsGot[0]);

        $settings3 = new DirectoryAnalysisSettings(__DIR__ . '/Models', $picker);
        $analyser->addDirectorySettings($settings3);

        $settingsGot = $analyser->getDirectoriesSettings();
        $this->assertCount(2, $settingsGot);
        $this->assertEquals($settings2, $settingsGot[0]);
        $this->assertEquals($settings3, $settingsGot[1]);
    }

    /**
     * @requires PHP >= 8.0
     *
     * @covers ::scanComponents
     */
    public function testAnalysisPhp8()
    {
        $simplePicker = new ComponentsPickerSimple();

        $settings = [
            new DirectoryAnalysisSettings(TEST_DIR . '/ExampleComponentsNested', $simplePicker),
        ];

        $parser = new FileParserPhp8();

        $analyser = new ComponentsAnalyser($settings, $parser);

        $result = $analyser->scanComponents();

        $this->assertInstanceOf(AnalysisDataPhpRepository::class, $result);

        // @todo: check results
        $this->assertEquals(1, 0);
    }
}