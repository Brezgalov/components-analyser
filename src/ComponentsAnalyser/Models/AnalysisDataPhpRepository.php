<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models;

class AnalysisDataPhpRepository implements IAnalysisDataRepository
{
    /**
     * @var array
     */
    protected $componentsDirToNamesMap = [];

    /**
     * @var array
     */
    protected $classFiles = [];

    /**
     * @var array
     */
    protected $componentsOwnClasses = [];

    /**
     * @var array
     */
    protected $classesOwnedByComponents = [];

    /**
     * @var array
     */
    protected $componentsDependencies = [];

    /**
     * @var array
     */
    protected $dependencyComponents = [];

    /**
     * @var array
     */
    protected $classDependencies = [];

    /**
     * @var array
     */
    protected $dependencyClasses = [];

    /**
     * @param string $componentPath
     * @param string $componentName
     */
    public function addComponentDirToMap(string $componentPath, string $componentName)
    {
        $this->componentsDirToNamesMap[$componentPath] = $componentName;
    }

    /**
     * @param string $componentPath
     * @param string $classFullName
     */
    public function addComponentOwnClass(string $componentPath, string $classFullName)
    {
        // bind map component => class
        $this->componentsOwnClasses[$componentPath][$classFullName] = true;

        // bind map class => component
        $this->classesOwnedByComponents[$classFullName] = $componentPath;
    }

    /**
     * @param string $classFullName
     * @param string $filePath
     */
    public function addClassFile(string $classFullName, string $filePath)
    {
        // bind path to class
        $this->classFiles[$classFullName] = $filePath;
    }

    /**
     * @param string $componentPath
     * @param string $className
     * @param string $dependencyClassName
     */
    public function addComponentDependency(string $componentPath, string $className, string $dependencyClassName)
    {
        // bind map component => dependency class
        $this->componentsDependencies[$componentPath][$dependencyClassName] = true;

        // bind map dependency class => component
        $this->dependencyComponents[$dependencyClassName][$componentPath] = true;

        // bind map component file => dependency class
        $this->classDependencies[$className][$dependencyClassName] = true;

        // bind map dependency class => component file
        $this->dependencyClasses[$dependencyClassName][$className] = true;
    }

    /**
     * @return array
     */
    public function getComponentsDirsList()
    {
        return array_keys($this->componentsDirToNamesMap);
    }

    /**
     * @param string $componentDir
     * @return string|null
     */
    public function getComponentNameByDir(string $componentDir)
    {
        return $this->componentsDirToNamesMap[$componentDir] ?? null;
    }

    /**
     * @param string $className
     * @return string|null
     */
    public function getClassFilePath(string $className)
    {
        return $this->classFiles[$className] ?? null;
    }

    /**
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentOwnClasses(string $compDir)
    {
        $classes = $this->componentsOwnClasses[$compDir] ?? null;

        return $classes ? array_keys($classes) : null;
    }

    /**
     * @param string $className
     * @return mixed|null
     */
    public function getClassComponent(string $className)
    {
        return $this->classesOwnedByComponents[$className] ?? null;
    }

    /**
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentDependenciesAll(string $compDir)
    {
        $dependencies = $this->componentsDependencies[$compDir] ?? null;

        return $dependencies ? array_keys($dependencies) : null;
    }

    /**
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentDependenciesExternal(string $compDir)
    {
        $dependencies = $this->componentsDependencies[$compDir] ?? [];
        $ownClasses = $this->componentsOwnClasses[$compDir] ?? [];

        foreach ($ownClasses as $className => $true) {
            unset($dependencies[$className]);
        }

        return array_values($dependencies);
    }

    /**
     * @param string $dependencyClassName
     * @return string[]|null
     */
    public function getDependencyComponents(string $dependencyClassName)
    {
        $components = $this->dependencyComponents[$dependencyClassName] ?? null;

        return $components ? array_keys($components) : null;
    }

    /**
     * @param string $className
     * @return string[]|null
     */
    public function getClassDependencies(string $className)
    {
        $dependencies = $this->classDependencies[$className] ?? null;

        return $dependencies ? array_keys($dependencies) : null;
    }

    /**
     * @param string $className
     * @return string[]|mixed
     */
    public function getClassExternalDependencies(string $className)
    {
        $classComponent = $this->getClassComponent($className);
        if (empty($classComponent)) {
            return [];
        }

        $componentOwnClasses = $this->componentsOwnClasses[$classComponent] ?? [];

        $classDependencies = $this->classDependencies[$className] ?? [];

        foreach ($componentOwnClasses as $className => $true){
            unset($classDependencies[$className]);
        }

        return array_values($classDependencies);
    }

    /**
     * @param string $dependencyClass
     * @return string[]|null
     */
    public function getDependencyClasses(string $dependencyClass)
    {
        $classes = $this->dependencyClasses[$dependencyClass] ?? null;

        return $classes ? array_keys($classes) : null;
    }
}