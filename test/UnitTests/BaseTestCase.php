<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    const FILE_COMP_A_TRIGGER_B_LOCAL = "MyFolder/MySubFolder/TriggerB.php";
    const FILE_COMP_A_TRIGGER_C_LOCAL = "MyFolder/TriggerC.php";
    const FILE_COMP_A_CONFIG_JSON_LOCAL = "config.json";
    const FILE_COMP_A_LOCAL = "CompA.php";

    const FILE_A_TRIGGER_B_ABSOLUTE = TEST_DIR . "/ExampleComponents/A/" . self::FILE_COMP_A_TRIGGER_B_LOCAL;
    const FILE_A_TRIGGER_C_ABSOLUTE = TEST_DIR . "/ExampleComponents/A/" . self::FILE_COMP_A_TRIGGER_C_LOCAL;
    const FILE_A_CONFIG_JSON_ABSOLUTE = TEST_DIR . "/ExampleComponents/A/" . self::FILE_COMP_A_CONFIG_JSON_LOCAL;
    const FILE_COMP_A_ABSOLUTE = TEST_DIR . "/ExampleComponents/A/" . self::FILE_COMP_A_LOCAL;

    const FILE_COMP_B_LOCAL = "CompB.php";
    const FILE_COMP_B_ABSOLUTE = TEST_DIR . "/ExampleComponents/B/" . self::FILE_COMP_B_LOCAL;

    const FILE_COMP_C_LOCAL = "CompC.php";
    const FILE_COMP_C_ABSOLUTE = TEST_DIR . "/ExampleComponents/C/" . self::FILE_COMP_C_LOCAL;
}