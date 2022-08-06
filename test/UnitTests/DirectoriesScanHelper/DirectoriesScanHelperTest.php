<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\DirectoriesScanHelper;

use Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\DirectoriesScanHelper;
use Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\MaxDeepOverflowException;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class DirectoriesScanHelperTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\DirectoriesScanHelper
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\DirectoriesScanHelper
 */
class DirectoriesScanHelperTest extends BaseTestCase
{
    /**
     * @covers ::scanDirContents
     */
    public function testScanDirContents()
    {
        $helper = new DirectoriesScanHelper();

        $contents = $helper->scanDirContents(TEST_DIR . "/ExampleComponentsCircled");

        $this->assertIsArray($contents);
        $this->assertCount(3, $contents);

        $this->assertTrue(in_array("A", $contents));
        $this->assertTrue(in_array("B", $contents));
        $this->assertTrue(in_array("C", $contents));

        $contents = $helper->scanDirContents(TEST_DIR . "/" . uniqid());

        $this->assertFalse($contents);

        $contents = $helper->scanDirContents(TEST_DIR . "/_dir.php");

        $this->assertFalse($contents);
    }

    /**
     * @throws MaxDeepOverflowException
     *
     * @covers ::getDirectoryFiles
     * @covers ::scanDirContents
     */
    public function testGetDirectoryFiles()
    {
        $helper = new DirectoriesScanHelper();

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponentsCircled/" . uniqid());

        $this->assertFalse($files);

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponentsCircled/A", false);


        $this->assertIsArray($files);
        $this->assertCount(3, $files);
        $this->assertTrue(in_array(static::FILE_COMP_A_TRIGGER_B_LOCAL, $files));
        $this->assertTrue(in_array(static::FILE_COMP_A_TRIGGER_C_LOCAL, $files));
        $this->assertTrue(in_array(static::FILE_COMP_A_LOCAL, $files));

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponentsCircled/A");

        $this->assertIsArray($files);
        $this->assertCount(3, $files);
        $this->assertTrue(in_array(static::FILE_A_TRIGGER_B_ABSOLUTE, $files));
        $this->assertTrue(in_array(static::FILE_A_TRIGGER_C_ABSOLUTE, $files));
        $this->assertTrue(in_array(static::FILE_COMP_A_ABSOLUTE, $files));

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponentsCircled/A", false, 1, false);

        $this->assertIsArray($files);
        $this->assertCount(2, $files);
        $this->assertTrue(in_array(static::FILE_COMP_A_TRIGGER_C_LOCAL, $files));
        $this->assertTrue(in_array(static::FILE_COMP_A_LOCAL, $files));

        try {
            $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponentsCircled/A", false, 1, true);
        } catch (MaxDeepOverflowException $ex) {

        }

        $this->assertInstanceOf(MaxDeepOverflowException::class, $ex);

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponentsCircled/A", false, 100, true, false);

        $this->assertIsArray($files);
        $this->assertCount(4, $files);
        $this->assertTrue(in_array(static::FILE_COMP_A_CONFIG_JSON_LOCAL, $files));
    }
}