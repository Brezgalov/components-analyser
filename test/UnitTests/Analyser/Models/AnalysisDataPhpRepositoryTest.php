<?php

namespace Brezgalov\ComponentsAnalyser\UnitTests\Analyser\Models;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisDataPhpRepository;
use Brezgalov\ComponentsAnalyser\UnitTests\BaseTestCase;

/**
 * Class AnalysisDataPhpRepositoryTest
 * @package Brezgalov\ComponentsAnalyser\UnitTests\Analyser\Models
 *
 * @coversDefaultClass \Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisDataPhpRepository
 */
class AnalysisDataPhpRepositoryTest extends BaseTestCase
{
    /**
     * @covers ::addClassFile
     * @covers ::getClassFilePath
     */
    public function testClassPath()
    {
        $dataRepository = new AnalysisDataPhpRepository();

        $dataRepository->addClassFile(self::class, __DIR__);

        $path = $dataRepository->getClassFilePath(self::class);

        $this->assertEquals(__DIR__, $path);
    }

    /**
     * @covers ::addComponentDirToMap
     * @covers ::getComponentNameByDir
     * @covers ::getComponentsDirsList
     */
    public function testComponentsDirectories()
    {
        $dataRepository = new AnalysisDataPhpRepository();

        $dataRepository->addComponentDirToMap('/path/A', 'A');
        $dataRepository->addComponentDirToMap('/path/B', 'B');

        $directories = $dataRepository->getComponentsDirsList();

        $this->assertCount(2, $directories);
        $this->assertTrue(in_array('/path/A', $directories));
        $this->assertTrue(in_array('/path/B', $directories));

        $this->assertEquals(
            'A',
            $dataRepository->getComponentNameByDir('/path/A')
        );

        $this->assertEquals(
            'B',
            $dataRepository->getComponentNameByDir('/path/B')
        );
    }

    /**
     * @covers ::addComponentOwnClass
     * @covers ::getComponentOwnClasses
     * @covers ::getComponentNameByDir
     * @covers ::getClassComponent
     */
    public function testComponentOwnClasses()
    {
        $dataRepository = new AnalysisDataPhpRepository();

        $dataRepository->addComponentOwnClass('/path/A', 'MyOwnClass1');
        $dataRepository->addComponentOwnClass('/path/A', 'MyOwnClass2');

        $classes = $dataRepository->getComponentOwnClasses('/path/A');

        $this->assertCount(2, $classes);
        $this->assertTrue(in_array('MyOwnClass1', $classes));
        $this->assertTrue(in_array('MyOwnClass2', $classes));

        $this->assertEquals(
            '/path/A',
            $dataRepository->getClassComponent('MyOwnClass1')
        );
        $this->assertEquals(
            '/path/A',
            $dataRepository->getClassComponent('MyOwnClass2')
        );

        $compName = $dataRepository->getComponentNameByDir('/path/A');

        $this->assertNull($compName);

        $list = $dataRepository->getComponentsDirsList();

        $this->assertCount(1, $list);
        $this->assertEquals('/path/A', $list[0]);
    }

    /**
     * @covers ::addComponentDependency
     * @covers ::getComponentDependenciesClassNamesAll
     * @covers ::getClassDependantComponents
     * @covers ::getClassDependencies
     */
    public function testOwnDependencies()
    {
        $dataRepository = new AnalysisDataPhpRepository();

        // lets pretend AClass2 was not bind by some reason
        $dataRepository->addComponentOwnClass('/path/A', 'AClass1');

        // class2 uses class1
        $dataRepository->addComponentDependency('/path/A', 'AClass2', 'AClass1');

        $dependencies = $dataRepository->getComponentDependenciesClassNamesAll('/path/A');

        $this->assertNotEmpty($dependencies);
        $this->assertCount(1, $dependencies);
        $this->assertEquals('AClass1', $dependencies[0]);

        // class 1 used by class2, so class1 used in /path/A
        $class1DependentComponents = $dataRepository->getClassDependantComponents('AClass1');

        $this->assertCount(1, $class1DependentComponents);
        $this->assertEquals('/path/A', $class1DependentComponents[0]);

        // class 2 not used anywhere
        $class2DependentComponents = $dataRepository->getClassDependantComponents('AClass2');
        $this->assertEmpty($class2DependentComponents);

        // class 1 uses nothing
        $class1Dependencies = $dataRepository->getClassDependencies('AClass1');
        $this->assertEmpty($class1Dependencies);

        // class 2 uses class 1
        $class2Dependencies = $dataRepository->getClassDependencies('AClass2');
        $this->assertNotEmpty($class2Dependencies);
        $this->assertCount(1, $class2Dependencies);
        $this->assertEquals('AClass1', $class2Dependencies[0]);
    }

    /**
     * @covers ::addComponentDependency
     * @covers ::getComponentDependenciesClassNamesAll
     * @covers ::getClassDependantComponents
     * @covers ::getClassesDependantTo
     * @covers ::getComponentExternalClassDependencies
     */
    public function testDependencies()
    {
        $dataRepository = new AnalysisDataPhpRepository();

        $dataRepository->addComponentOwnClass('/path/A', 'AClass1');
        $dataRepository->addComponentOwnClass('/path/A', 'AClass2');

        $dataRepository->addComponentDependency('/path/B', 'BClass1', 'AClass1');
        $dataRepository->addComponentDependency('/path/B', 'BClass1', 'BClass2');

        $dataRepository->addComponentDependency('/path/B', 'BClass2', 'AClass2');

        $compDirs = $dataRepository->getComponentsDirsList();

        $this->assertCount(2, $compDirs);
        $this->assertTrue(in_array('/path/A', $compDirs));
        $this->assertTrue(in_array('/path/B', $compDirs));

        $bClasses = $dataRepository->getComponentOwnClasses('/path/B');

        $this->assertCount(2, $bClasses);
        $this->assertTrue(in_array('BClass1', $bClasses));
        $this->assertTrue(in_array('BClass2', $bClasses));

        $aCompDependencies = $dataRepository->getComponentDependenciesClassNamesAll('/path/A');

        $this->assertEmpty($aCompDependencies);

        $bCompDependencies = $dataRepository->getComponentDependenciesClassNamesAll('/path/B');

        $this->assertNotEmpty($bCompDependencies);
        $this->assertCount(3, $bCompDependencies);
        $this->assertTrue(in_array('AClass1', $bCompDependencies));
        $this->assertTrue(in_array('AClass2', $bCompDependencies));
        $this->assertTrue(in_array('BClass2', $bCompDependencies));

        // b1 is not used by any component
        $b1DependantComponents = $dataRepository->getClassDependantComponents('BClass1');
        $this->assertEmpty($b1DependantComponents);

        // b1 is not used by any class
        $b1ClassesDependantTo = $dataRepository->getClassesDependantTo('BClass1');
        $this->assertEmpty($b1ClassesDependantTo);

        // b1 uses b2 and a1
        $b1Dependencies = $dataRepository->getClassDependencies('BClass1');
        $this->assertNotEmpty($b1Dependencies);
        $this->assertCount(2, $b1Dependencies);
        $this->assertTrue(in_array('BClass2', $b1Dependencies));
        $this->assertTrue(in_array('AClass1', $b1Dependencies));

        // b2 is used by b1 [comp B]
        $b2DependantComponents = $dataRepository->getClassDependantComponents('BClass2');
        $this->assertNotEmpty($b2DependantComponents);
        $this->assertCount(1, $b2DependantComponents);
        $this->assertEquals('/path/B', $b2DependantComponents[0]);

        // b2 is used by b1 class
        $b2ClassesDependantTo = $dataRepository->getClassesDependantTo('BClass2');
        $this->assertNotEmpty($b2ClassesDependantTo);
        $this->assertCount(1, $b2ClassesDependantTo);
        $this->assertEquals('BClass1', $b2ClassesDependantTo[0]);

        // b2 uses a2
        $b2Dependencies = $dataRepository->getClassDependencies('BClass2');
        $this->assertNotEmpty($b2Dependencies);
        $this->assertCount(1, $b2Dependencies);
        $this->assertTrue(in_array('AClass2', $b2Dependencies));

        // a1 is used by b1 [comp B]
        $a1DependantComponents = $dataRepository->getClassDependantComponents('AClass1');
        $this->assertNotEmpty($a1DependantComponents);
        $this->assertCount(1, $a1DependantComponents);
        $this->assertEquals('/path/B', $a1DependantComponents[0]);

        // a1 is used by b1 class
        $a1ClassesDependantTo = $dataRepository->getClassesDependantTo('AClass1');
        $this->assertNotEmpty($a1ClassesDependantTo);
        $this->assertCount(1, $a1ClassesDependantTo);
        $this->assertEquals('BClass1', $a1ClassesDependantTo[0]);

        // a1 uses nothing
        $a1Dependencies = $dataRepository->getClassDependencies('AClass1');
        $this->assertEmpty($a1Dependencies);

        // a2 is used by b2 [comp B]
        $a2DependantComponents = $dataRepository->getClassDependantComponents('AClass2');
        $this->assertNotEmpty($a2DependantComponents);
        $this->assertCount(1, $a2DependantComponents);
        $this->assertEquals('/path/B', $a2DependantComponents[0]);

        // a2 is used by b2 class
        $a2ClassesDependantTo = $dataRepository->getClassesDependantTo('AClass2');
        $this->assertNotEmpty($a2ClassesDependantTo);
        $this->assertCount(1, $a2ClassesDependantTo);
        $this->assertEquals('BClass2', $a2ClassesDependantTo[0]);

        // a2 uses nothing
        $a2Dependencies = $dataRepository->getClassDependencies('AClass2');
        $this->assertEmpty($a2Dependencies);

        // A depends on nothing
        $aExternalClasses = $dataRepository->getComponentExternalClassDependencies('/path/A');
        $this->assertEmpty($aExternalClasses);

        // B depends on A (and on itself, but its not external dependency)
        $bExternalClasses = $dataRepository->getComponentExternalClassDependencies('/path/B');
        $this->assertNotEmpty($bExternalClasses);
        $this->assertCount(2, $bExternalClasses);
        $this->assertTrue(in_array('AClass1', $bExternalClasses));
        $this->assertTrue(in_array('AClass2', $bExternalClasses));

        // a1 doesnt have external dependencies
        $a1ExternalClassDependencies = $dataRepository->getClassExternalClassDependencies('AClass1');
        $this->assertEmpty($a1ExternalClassDependencies);

        // a2 doesnt have external dependencies
        $a2ExternalClassDependencies = $dataRepository->getClassExternalClassDependencies('AClass2');
        $this->assertEmpty($a2ExternalClassDependencies);

        // b1 depends on external a1
        $b1ExternalClassDependencies = $dataRepository->getClassExternalClassDependencies('BClass1');
        $this->assertNotEmpty($b1ExternalClassDependencies);
        $this->assertCount(1, $b1ExternalClassDependencies);
        $this->assertEquals('AClass1', $b1ExternalClassDependencies[0]);

        // b2 depends on external a2
        $b2ExternalClassDependencies = $dataRepository->getClassExternalClassDependencies('BClass2');
        $this->assertNotEmpty($b2ExternalClassDependencies);
        $this->assertCount(1, $b2ExternalClassDependencies);
        $this->assertEquals('AClass2', $b2ExternalClassDependencies[0]);
    }
}
