<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\FileParser\Models;

use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class FileParseResultTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\FileParser\Models
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult
 */
class FileParseResultTest extends BaseTestCase
{
    /**
     * @covers ::getUseDependencies
     */
    public function testConstructor()
    {
        $array = [
            '213',
            '2312',
        ];

        $result = new FileParseResult();
        $result->useClasses = $array;

        $this->assertEquals($array, $result->getUseDependencies());
    }
}