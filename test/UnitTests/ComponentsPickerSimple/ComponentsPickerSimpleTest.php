<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\ComponentsPickerSimple;

use Brezgalov\ComponentsAnalyser\ComponentsPickerSimple\ComponentsPickerSimple;
use Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\DirectoriesScanHelper;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class ComponentsPickerSimpleTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\ComponentsPickerSimple
 *
 * @coversDefaultClass  \Brezgalov\ComponentsAnalyser\ComponentsPickerSimple\ComponentsPickerSimple
 */
class ComponentsPickerSimpleTest extends BaseTestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $simplePicker = new ComponentsPickerSimple();

        $this->assertInstanceOf(DirectoriesScanHelper::class, $simplePicker->dirHelper);
    }

    /**
     * @throws \Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\MaxDeepOverflowException
     *
     * @covers \Brezgalov\ComponentsAnalyser\ComponentsPicker\ComponentsPicker::setComponentsPrefix
     * @covers \Brezgalov\ComponentsAnalyser\ComponentsPicker\ComponentsPicker::prepareComponentName
     * @covers ::getComponentsList
     */
    public function testPrefix()
    {
        $simplePicker = new ComponentsPickerSimple();

        $simplePicker->setComponentsPrefix('test/');
        $components = $simplePicker->getComponentsList(TEST_DIR . '/ExampleComponents');

        $this->assertCount(4, $components);

        $this->assertArrayHasKey('test/A', $components);
        $this->assertArrayHasKey('test/B', $components);
        $this->assertArrayHasKey('test/C', $components);
        $this->assertArrayHasKey('test/D', $components);
    }

    /**
     * @covers ::__construct
     * @covers ::getComponentsList
     */
    public function testComponentsPicking()
    {
        $simplePicker = new ComponentsPickerSimple();

        $components = $simplePicker->getComponentsList(TEST_DIR . '/' . uniqid());
        $this->assertFalse($components);

        $emptyFolder = TEST_DIR . '/' . uniqid(time() . '_');
        mkdir($emptyFolder);

        $components = $simplePicker->getComponentsList($emptyFolder);
        $this->assertIsArray($components);
        $this->assertEmpty($components);

        rmdir($emptyFolder);

        $components = $simplePicker->getComponentsList(TEST_DIR . '/ExampleComponents');

        $this->assertCount(4, $components);

        $this->assertArrayHasKey('A', $components);
        $this->assertArrayHasKey('B', $components);
        $this->assertArrayHasKey('C', $components);
        $this->assertArrayHasKey('D', $components);
        
        $this->assertIsArray($components['A']->files);
        $this->assertCount(4, $components['A']->files);
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/CompA.php', $components['A']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php', $components['A']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Interface1.php', $components['A']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Base/BaseClass1.php', $components['A']->files));

        $this->assertIsArray($components['B']->files);
        $this->assertCount(3, $components['B']->files);
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/B/CompB.php', $components['B']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/B/empty.php', $components['B']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/B/Classes/ChildClass.php', $components['B']->files));

        $this->assertIsArray($components['C']->files);
        $this->assertCount(2, $components['C']->files);
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/C/CompC.php', $components['C']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/C/config.php', $components['C']->files));

        $this->assertIsArray($components['D']->files);
        $this->assertCount(3, $components['D']->files);
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/D/CompD.php', $components['D']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/D/includes.php', $components['D']->files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/D/requires.php', $components['D']->files));
    }
}