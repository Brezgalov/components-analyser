<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners;

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ExtendsScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ImplementsScanner;
use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\IScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class ImplementsScannerTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParser\CodeScanners
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ImplementsScanner
 */
class ImplementsScannerTest extends BaseTestCase
{
    /**
     * @covers ::passToken
     * @covers ::storeScanResults
     */
    public function testImplementsSingleWord()
    {
        $codeFile = TEST_DIR . '/ExampleComponents/A/Classes/Class1.php';

        $this->assertTrue(is_file($codeFile));

        $code = file_get_contents($codeFile);
        $this->assertNotEmpty($code);

        $tokens = token_get_all($code);
        $this->assertNotEmpty($tokens);

        $scanner = new ImplementsScanner();

        foreach ($tokens as $tokenInfo) {
            if (is_array($tokenInfo)) {
                list($tokenCode, $tokenVal, $fileStrNumber) = $tokenInfo;
                $tokenName = token_name($tokenCode);

                $scanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);
            }
        }

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('Interface1', $result->getImplements());
    }

    /**
     * @covers ::passToken
     * @covers ::storeScanResults
     */
    public function testImplementsFullyQualified()
    {
        $codeFile = TEST_DIR . '/ExampleComponents/A/Classes/Base/BaseClass1.php';

        $this->assertTrue(is_file($codeFile));

        $code = file_get_contents($codeFile);
        $this->assertNotEmpty($code);

        $tokens = token_get_all($code);
        $this->assertNotEmpty($tokens);

        $scanner = new ImplementsScanner();

        foreach ($tokens as $tokenInfo) {
            if (is_array($tokenInfo)) {
                list($tokenCode, $tokenVal, $fileStrNumber) = $tokenInfo;
                $tokenName = token_name($tokenCode);

                $scanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);
            }
        }

        $result = $scanner->storeScanResults(new FileParseResult());

        $this->assertEquals('ExampleComponents\A\Classes\Interface1', $result->getImplements());
    }
}