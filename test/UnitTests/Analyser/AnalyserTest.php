<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\Analyser;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\ComponentsAnalyser;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisDataPhpRepository;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\DirectoryAnalysisSettings;
use Brezgalov\ComponentsAnalyser\ComponentsPickerSimple\ComponentsPickerSimple;
use Brezgalov\ComponentsAnalyser\FileParser\FileParser;
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
        $anyPossibleParser = new FileParser();

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

    /**    *
     * @covers ::scanComponents
     */
    public function testAnalysis()
    {
        $simplePicker = new ComponentsPickerSimple();

        $settings = [
            new DirectoryAnalysisSettings(TEST_DIR . '/ExampleComponents', $simplePicker),
        ];

        $parser = new FileParser();

        $analyser = new ComponentsAnalyser($settings, $parser);

        $result = $analyser->scanComponents();

        $this->assertInstanceOf(AnalysisDataPhpRepository::class, $result);

        // Test components scanned correctly
        $components = [
            'A' => TEST_DIR . '/ExampleComponents/A',
            'B' => TEST_DIR . '/ExampleComponents/B',
            'C' => TEST_DIR . '/ExampleComponents/C',
            'D' => TEST_DIR . '/ExampleComponents/D',
        ];

        $this->assertEquals(array_values($components), $result->getComponentsDirsList());

        foreach ($components as $compName => $compDir) {
            $name = $result->getComponentNameByDir($compDir);
            $this->assertEquals($compName, $name);
        }

        // Test component files scanned correctly
        $componentsClasses = [
            TEST_DIR . '/ExampleComponents/A' => [
                'ExampleComponents\\A\\CompA',
                'ExampleComponents\\A\\Classes\\Class1',
                'ExampleComponents\\A\\Classes\\Interface1',
                'ExampleComponents\\A\\Classes\\Interface2',
                'ExampleComponents\\A\\Classes\\Base\\BaseClass1',
                'ExampleComponents\\A\\Classes\\Base\\BaseInterface',
            ],
            TEST_DIR . '/ExampleComponents/B' => [
                'ExampleComponents\\B\\CompB',
                'ExampleComponents\\B\\Classes\\ChildClass',
            ],
            TEST_DIR . '/ExampleComponents/C' => [
                'ExampleComponents\\C\\CompC',
            ],
            TEST_DIR . '/ExampleComponents/D' => [
                'ExampleComponents\\D\\CompD',
            ],
        ];

        foreach ($componentsClasses as $compDir => $classes) {
            $classesFound = $result->getComponentOwnClasses($compDir);

            $this->assertCount(count($classes), $classesFound);
            foreach ($classes as $class) {
                $this->assertContains($class, $classesFound);
            }
        }

        // Test components dependencies
        $compDependencies = [
            TEST_DIR . '/ExampleComponents/A' => [
                TEST_DIR . '/ExampleComponents/B',
            ],
            TEST_DIR . '/ExampleComponents/B' => [
                TEST_DIR . '/ExampleComponents/A',
                TEST_DIR . '/ExampleComponents/C',
            ],
            TEST_DIR . '/ExampleComponents/C' => [
                TEST_DIR . '/ExampleComponents/A',
            ],
            TEST_DIR . '/ExampleComponents/D' => [
                TEST_DIR . '/ExampleComponents/B',
            ],
        ];

        foreach ($compDependencies as $component => $depComponents) {
            $depsFound = $result->getComponentExternalComponentDependencies($component);

            $this->assertCount(count($depComponents), $depsFound);

            foreach ($depComponents as $depComponent) {
                $this->assertContains($depComponent, $depsFound);
            }
        }

        //@todo: bug: some A class are marked as external dependency for A

        // Test components external class dependencies
        // Test class external class dependencies

        // check internal dependencies?
    }
}