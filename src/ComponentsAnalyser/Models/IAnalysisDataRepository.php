<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models;

interface IAnalysisDataRepository
{
    /**
     * Adds component directory and name to memory
     *
     * @param string $componentPath
     * @param string $componentName
     */
    public function addComponentDirToMap(string $componentPath, string $componentName);

    /**
     * Binds component own class
     *
     * @param string $componentPath
     * @param string $classFullName
     */
    public function addComponentOwnClass(string $componentPath, string $classFullName);

    /**
     * Binds class to its file
     *
     * @param string $classFullName
     * @param string $filePath
     */
    public function addClassFile(string $classFullName, string $filePath);

    /**
     * Component <$componentPath> has class <$ownClassName> that uses <$dependencyClassName>
     *
     * @param string $componentPath
     * @param string $ownClassName
     * @param string $dependencyClassName
     */
    public function addComponentDependency(string $componentPath, string $ownClassName, string $dependencyClassName);

    /**
     * List all components dirs
     *
     * @return array
     */
    public function getComponentsDirsList();

    /**
     * Find component name by its directory
     *
     * @param string $componentDir
     * @return string|null
     */
    public function getComponentNameByDir(string $componentDir);

    /**
     * Find class file path by its name
     *
     * @param string $className
     * @return string|null
     */
    public function getClassFilePath(string $className);

    /**
     * What classes belong to component
     *
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentOwnClasses(string $compDir);

    /**
     * Get component that class belongs to
     *
     * @param string $className
     * @return mixed|null
     */
    public function getClassComponent(string $className);

    /**
     * Get list of all classes that use this component
     *
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentDependenciesClassNamesAll(string $compDir);

    /**
     * What classes does component use
     *
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentExternalClassDependencies(string $compDir);

    /**
     * What components use this class
     *
     * @param string $dependencyClassName
     * @return string[]|null
     */
    public function getClassDependantComponents(string $dependencyClassName);

    /**
     * What other classes does this class use
     *
     * @param string $className
     * @return string[]|null
     */
    public function getClassDependencies(string $className);

    /**
     * What classes use $dependencyClass
     *
     * @param string $dependencyClass
     * @return string[]|null
     */
    public function getClassesDependantTo(string $dependencyClass);

    /**
     * What classes does class use
     *
     * @param string $className
     * @return string[]|mixed
     */
    public function getClassExternalClassDependencies(string $className);

    /**
     * What components use this component
     *
     * @param string $compDir
     * @return string[]
     */
    public function getComponentExternalComponentDependencies(string $compDir);
}