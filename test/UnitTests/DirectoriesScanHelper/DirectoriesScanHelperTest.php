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

        $contents = $helper->scanDirContents(TEST_DIR . "/ExampleComponents");

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

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/" . uniqid());

        $this->assertFalse($files);

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/A", false);

        $fileTriggerBLocal = "MyFolder/MySubFolder/TriggerB.php";
        $fileTriggerCLocal = "MyFolder/TriggerC.php";
        $compAFileLocal = "CompA.php";

        $this->assertIsArray($files);
        $this->assertCount(3, $files);
        $this->assertTrue(in_array($fileTriggerBLocal, $files));
        $this->assertTrue(in_array($fileTriggerCLocal, $files));
        $this->assertTrue(in_array($compAFileLocal, $files));

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/A");

        $fileTriggerBAbsolute = TEST_DIR . "/ExampleComponents/A/{$fileTriggerBLocal}";
        $fileTriggerCAbsolute = TEST_DIR . "/ExampleComponents/A/{$fileTriggerCLocal}";
        $compAFileAbsolute = TEST_DIR . "/ExampleComponents/A/{$compAFileLocal}";

        $this->assertIsArray($files);
        $this->assertCount(3, $files);
        $this->assertTrue(in_array($fileTriggerBAbsolute, $files));
        $this->assertTrue(in_array($fileTriggerCAbsolute, $files));
        $this->assertTrue(in_array($compAFileAbsolute, $files));

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/A", false, 1, false);

        $this->assertIsArray($files);
        $this->assertCount(2, $files);
        $this->assertTrue(in_array($fileTriggerCLocal, $files));
        $this->assertTrue(in_array($compAFileLocal, $files));

        try {
            $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/A", false, 1, true);
        } catch (MaxDeepOverflowException $ex) {

        }

        $this->assertInstanceOf(MaxDeepOverflowException::class, $ex);
    }
}