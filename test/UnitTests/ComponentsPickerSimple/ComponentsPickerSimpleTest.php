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

        $components = $simplePicker->getComponentsList(TEST_DIR . '/ExampleComponentsCircled');

        $this->assertCount(3, $components);

        $this->assertArrayHasKey('A', $components);
        $this->assertArrayHasKey('B', $components);
        $this->assertArrayHasKey('C', $components);
        
        $this->assertIsArray($components['A']->files);
        $this->assertCount(3, $components['A']->files);
        $this->assertTrue(in_array(static::FILE_A_TRIGGER_B_ABSOLUTE, $components['A']->files));
        $this->assertTrue(in_array(static::FILE_A_TRIGGER_C_ABSOLUTE, $components['A']->files));
        $this->assertTrue(in_array(static::FILE_COMP_A_ABSOLUTE, $components['A']->files));

        $this->assertIsArray($components['B']->files);
        $this->assertCount(2, $components['B']->files);
        $this->assertTrue(in_array(static::FILE_COMP_B_ABSOLUTE, $components['B']->files));

        $this->assertIsArray($components['C']->files);
        $this->assertCount(1, $components['C']->files);
        $this->assertTrue(in_array(static::FILE_COMP_C_ABSOLUTE, $components['C']->files));
    }
}