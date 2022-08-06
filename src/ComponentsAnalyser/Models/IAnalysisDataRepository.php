<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models;

interface IAnalysisDataRepository
{
    /**
     * @param string $componentPath
     * @param string $componentName
     */
    public function addComponentDirToMap(string $componentPath, string $componentName);

    /**
     * @param string $componentPath
     * @param string $classFullName
     */
    public function addComponentOwnClass(string $componentPath, string $classFullName);

    /**
     * @param string $classFullName
     * @param string $filePath
     */
    public function addClassFile(string $classFullName, string $filePath);

    /**
     * @param string $componentPath
     * @param string $className
     * @param string $dependencyClassName
     */
    public function addComponentDependency(string $componentPath, string $className, string $dependencyClassName);

    /**
     * @return array
     */
    public function getComponentsDirsList();

    /**
     * @param string $componentDir
     * @return string|null
     */
    public function getComponentNameByDir(string $componentDir);

    /**
     * @param string $className
     * @return string|null
     */
    public function getClassFilePath(string $className);

    /**
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentOwnClasses(string $compDir);

    /**
     * @param string $className
     * @return mixed|null
     */
    public function getClassComponent(string $className);

    /**
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentDependenciesAll(string $compDir);

    /**
     * @param string $compDir
     * @return string[]|null
     */
    public function getComponentDependenciesExternal(string $compDir);

    /**
     * @param string $dependencyClassName
     * @return string[]|null
     */
    public function getDependencyComponents(string $dependencyClassName);

    /**
     * @param string $className
     * @return string[]|null
     */
    public function getClassDependencies(string $className);

    /**
     * @param string $className
     * @return string[]|mixed
     */
    public function getClassExternalDependencies(string $className);

    /**
     * @param string $dependencyClass
     * @return string[]|null
     */
    public function getDependencyClasses(string $dependencyClass);
}