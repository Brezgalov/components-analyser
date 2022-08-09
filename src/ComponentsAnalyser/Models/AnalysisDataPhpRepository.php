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
     * Adds component directory and name to memory
     *
     * @param string $componentPath
     * @param string $componentName
     */
    public function addComponentDirToMap(string $componentPath, string $componentName)
    {
        $this->componentsDirToNamesMap[$componentPath] = $componentName;
    }

    /**
     * Binds component own class
     *
     * @param string $componentPath
     * @param string $classFullName
     */
    public function addComponentOwnClass(string $componentPath, string $classFullName)
    {
        // bind map component => class
        $this->componentsOwnClasses[$componentPath][$classFullName] = true;

        // bind map class => component
        $this->classesOwnedByComponents[$classFullName] = $componentPath;

        if (!array_key_exists($componentPath, $this->componentsDirToNamesMap)) {
            $this->componentsDirToNamesMap[$componentPath] = null;
        }
    }

    /**
     * Binds class to its file
     *
     * @param string $classFullName
     * @param string $filePath
     */
    public function addClassFile(string $classFullName, string $filePath)
    {
        // bind path to class
        $this->classFiles[$classFullName] = $filePath;
    }

    /**
     * Component <$componentPath> has class <$ownClassName> that uses <$dependencyClassName>
     *
     * @param string $componentPath
     * @param string $ownClassName
     * @param string $dependencyClassName
     */
    public function addComponentDependency(string $componentPath, string $ownClassName, string $dependencyClassName)
    {
        // bind own class to component
        $this->addComponentOwnClass(
            $componentPath,
            $ownClassName
        );

        // bind map component => dependency class
        $this->componentsDependencies[$componentPath][$dependencyClassName] = true;

        // bind map dependency class => component
        $this->dependencyComponents[$dependencyClassName][$componentPath] = true;

        // bind map component file => dependency class
        $this->classDependencies[$ownClassName][$dependencyClassName] = true;

        // bind map dependency class => component file
        $this->dependencyClasses[$dependencyClassName][$ownClassName] = true;
    }

    /**
     * List all components dirs
     * @return array
     */
    public function getComponentsDirsList()
    {
        return array_keys($this->componentsDirToNamesMap);
    }

    /**
     * Find component name by its directory
     *
     * @param string $componentDir
     * @return string|null
     */
    public function getComponentNameByDir(string $componentDir)
    {
        return $this->componentsDirToNamesMap[$componentDir] ?? null;
    }

    /**
     * Find class file path by its name
     *
     * @param string $className
     * @return string|null
     */
    public function getClassFilePath(string $className)
    {
        return $this->classFiles[$className] ?? null;
    }

    /**
     * What classes belong to component
     *
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentOwnClasses(string $compDir)
    {
        $classes = $this->componentsOwnClasses[$compDir] ?? null;

        return $classes ? array_keys($classes) : null;
    }

    /**
     * Get component that class belongs to
     *
     * @param string $className
     * @return string|null
     */
    public function getClassComponent(string $className)
    {
        return $this->classesOwnedByComponents[$className] ?? null;
    }

    /**
     * Get list of all classes that use this component
     *
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentDependenciesClassNamesAll(string $compDir)
    {
        $dependencies = $this->componentsDependencies[$compDir] ?? null;

        return $dependencies ? array_keys($dependencies) : null;
    }

    /**
     * What classes does component use
     *
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentExternalClassDependencies(string $compDir)
    {
        $dependencies = $this->componentsDependencies[$compDir] ?? [];
        $ownClasses = $this->componentsOwnClasses[$compDir] ?? [];

        foreach ($ownClasses as $className => $true) {
            unset($dependencies[$className]);
        }

        return array_keys($dependencies);
    }

    /**
     * What components use this class
     *
     * @param string $dependencyClassName
     * @return string[]|null
     */
    public function getClassDependantComponents(string $dependencyClassName)
    {
        $components = $this->dependencyComponents[$dependencyClassName] ?? null;

        return $components ? array_keys($components) : null;
    }

    /**
     * What other classes does this class use
     *
     * @param string $className
     * @return string[]|null
     */
    public function getClassDependencies(string $className)
    {
        $dependencies = $this->classDependencies[$className] ?? null;

        return $dependencies ? array_keys($dependencies) : null;
    }

    /**
     * What classes use $dependencyClass
     *
     * @param string $dependencyClass
     * @return string[]|null
     */
    public function getClassesDependantTo(string $dependencyClass)
    {
        $classes = $this->dependencyClasses[$dependencyClass] ?? null;

        return $classes ? array_keys($classes) : null;
    }

    /**
     * What classes does class use
     *
     * @param string $className
     * @return string[]|mixed
     */
    public function getClassExternalClassDependencies(string $className)
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

        return array_keys($classDependencies);
    }

    //@todo: implement ::getComponentExternalComponentDependencies
}