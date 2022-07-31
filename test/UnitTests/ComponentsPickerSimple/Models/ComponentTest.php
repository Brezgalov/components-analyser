<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\ComponentsPicker\Models;

use Brezgalov\ComponentsAnalyser\ComponentsPicker\Models\Component;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class ComponentTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\Component
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\ComponentsPicker\Models\Component
 */
class ComponentTest extends BaseTestCase
{
    /**
     * @covers ::__construct
     * @covers ::getRootDirectoryPath
     * @covers ::getId
     */
    public function testConstructor()
    {
        $comp = new Component('test-comp', __DIR__);

        $this->assertEquals('test-comp', $comp->getId());
        $this->assertEquals(__DIR__, $comp->getRootDirectoryPath());
    }
}