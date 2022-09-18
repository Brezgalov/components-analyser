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
        $this->assertCount(4, $contents);

        $this->assertTrue(in_array("A", $contents));
        $this->assertTrue(in_array("B", $contents));
        $this->assertTrue(in_array("C", $contents));
        $this->assertTrue(in_array("D", $contents));

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

        $this->assertIsArray($files);
        $this->assertCount(6, $files);
        $this->assertContains('CompA.php', $files);
        $this->assertContains('Classes/Class1.php', $files);
        $this->assertContains('Classes/Interface1.php', $files);
        $this->assertContains('Classes/Interface2.php', $files);
        $this->assertContains('Classes/Base/BaseClass1.php', $files);
        $this->assertContains('Classes/Base/BaseInterface.php', $files);

        $files = $helper->getDirectoryFiles(TEST_DIR . '/ExampleComponents/A');

        $this->assertIsArray($files);
        $this->assertCount(6, $files);
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/CompA.php', $files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Class1.php', $files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Interface1.php', $files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Interface2.php', $files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Base/BaseClass1.php', $files));
        $this->assertTrue(in_array(TEST_DIR . '/ExampleComponents/A/Classes/Base/BaseInterface.php', $files));

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/A", false, 1, false);

        $this->assertIsArray($files);
        $this->assertCount(4, $files);
        $this->assertTrue(in_array('CompA.php', $files));
        $this->assertTrue(in_array('Classes/Class1.php', $files));
        $this->assertTrue(in_array('Classes/Interface1.php', $files));
        $this->assertTrue(in_array('Classes/Interface2.php', $files));

        try {
            $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/A", false, 1, true);
        } catch (MaxDeepOverflowException $ex) {

        }

        $this->assertInstanceOf(MaxDeepOverflowException::class, $ex);

        $files = $helper->getDirectoryFiles(TEST_DIR . "/ExampleComponents/A", false, 100, true, false);

        $this->assertIsArray($files);
        $this->assertCount(7, $files);
        $this->assertTrue(in_array('config.json', $files));
    }
}