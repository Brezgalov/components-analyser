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
     * @covers ::addUseDependency
     * @covers ::getUseDependencies
     */
    public function testDependencies()
    {
        $array = [
            '213',
            '2312',
        ];

        $result = new FileParseResult();
        $result->addUseDependency('213');
        $result->addUseDependency('2312');

        $this->assertEquals($array, $result->getUseDependencies());

        $result->addUseDependency('213');
        $result->addUseDependency('2312');

        $this->assertEquals($array, $result->getUseDependencies());
    }

    /**
     * @covers ::setError
     * @covers ::getError
     */
    public function testError()
    {
        $result = new FileParseResult();
        $result->setError('123');

        $this->assertEquals('123', $result->getError());
    }

    /**
     * @covers ::setNamespace
     * @covers ::setClassName
     * @covers ::getClassName
     * @covers ::getNamespace
     * @covers ::getFullClassName
     */
    public function testClassName()
    {
        $result = new FileParseResult();
        $result->setNamespace('app\test');
        $result->setClassName('MyTestClass');

        $this->assertEquals('app\test', $result->getNamespace());
        $this->assertEquals('MyTestClass', $result->getClassName());
        $this->assertEquals('app\test\MyTestClass', $result->getFullClassName());
    }

    /**
     * @covers ::setIsClass
     * @covers ::setIsAbstract
     * @covers ::setIsInterface
     * @covers ::getIsClass
     * @covers ::getIsAbstract
     * @covers ::getIsInterface
     */
    public function testClassProperties()
    {
        $result = new FileParseResult();

        $this->assertFalse($result->getIsClass());
        $this->assertFalse($result->getIsAbstract());
        $this->assertFalse($result->getIsInterface());

        $result->setIsClass(true);
        $result->setIsAbstract(true);
        $result->setIsInterface(true);

        $this->assertTrue($result->getIsClass());
        $this->assertTrue($result->getIsAbstract());
        $this->assertTrue($result->getIsInterface());
    }
}