<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\Analyser\Models;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Exceptions\InvalidDirectoryException;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\DirectoryAnalysisSettings;
use Brezgalov\ComponentsAnalyser\ComponentsPickerSimple\ComponentsPickerSimple;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class DirectoryAnalysisSettingsTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\Analyser\Models
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\DirectoryAnalysisSettings
 */
class DirectoryAnalysisSettingsTest extends BaseTestCase
{
    /**
     * @covers ::__construct
     * @covers ::getPicker
     * @covers ::getDirectory
     */
    public function testSetup()
    {
        $picker = new ComponentsPickerSimple();

        try {
            $setting = new DirectoryAnalysisSettings('123gogo', $picker);
        } catch (InvalidDirectoryException $ex) {
            //silence is golden
        }

        $this->assertTrue(isset($ex));
        $this->assertNotEmpty($ex);

        $dirJammed = __DIR__ . '/../Models';

        $setting = new DirectoryAnalysisSettings($dirJammed, $picker);

        // make some changes
        $picker->dirHelper = null;

        $this->assertEquals(__DIR__, $setting->getDirectory());

        /** @var ComponentsPickerSimple $settingPicker */
        $settingPicker = $setting->getPicker();

        $this->assertInstanceOf(ComponentsPickerSimple::class, $settingPicker);
        $this->assertNotEmpty($settingPicker->dirHelper);

        // make more changes
        $settingPicker->dirHelper = null;

        /** @var ComponentsPickerSimple $settingPicker */
        $settingPicker = $setting->getPicker();

        $this->assertInstanceOf(ComponentsPickerSimple::class, $settingPicker);
        $this->assertNotEmpty($settingPicker->dirHelper);
    }
}