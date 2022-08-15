<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\Models;

interface IFileParseResult
{
    /**
     * @param string $val
     */
    public function setClassName(string $val);

    /**
     * @param string $val
     */
    public function setNamespace(string $val);

    /**
     * @param string $dependency
     */
    public function addUseDependency(string $dependency);

    /**
     * @param string $error
     */
    public function setError(string $error);

    /**
     * @param bool $val
     */
    public function setIsClass(bool $val);

    /**
     * @param bool $val
     */
    public function setIsAbstract(bool $val);

    /**
     * @param bool $val
     */
    public function setIsInterface(bool $val);

    /**
     * returns classes from use statements
     * @return string[]
     */
    public function getUseDependencies();

    /**
     * @return string|null
     */
    public function getError();

    /**
     * @return string
     */
    public function getNamespace();

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getFullClassName();

    /**
     * @return bool
     */
    public function getIsClass();

    /**
     * @return bool
     */
    public function getIsAbstract();

    /**
     * @return bool
     */
    public function getIsInterface();
}