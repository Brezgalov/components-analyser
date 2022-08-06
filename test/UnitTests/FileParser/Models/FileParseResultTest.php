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
    public function testDependencies()
    {
        $array = [
            '213',
            '2312',
        ];

        $result = new FileParseResult();
        $result->useClasses = $array;

        $this->assertEquals($array, $result->getUseDependencies());
    }

    /**
     * @covers ::getError
     */
    public function testError()
    {
        $result = new FileParseResult();
        $result->error = '123';

        $this->assertEquals('123', $result->getError());
    }

    /**
     * @covers ::getFullClassName
     */
    public function testClassName()
    {
        $result = new FileParseResult();
        $result->namespace = 'app\test';
        $result->className = 'MyTestClass';

        $this->assertEquals('app\test\MyTestClass', $result->getFullClassName());
    }
}