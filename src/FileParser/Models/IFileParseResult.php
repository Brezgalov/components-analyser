<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\Models;

// @todo: interface segregation required
interface IFileParseResult
{
    /**
     * @param string|null $val
     * @return IFileParseResult
     */
    public function setClassName(string $val = null);

    /**
     * @param string|null $val
     * @return IFileParseResult
     */
    public function setNamespace(string $val = null);

    /**
     * @param string|null $extends
     * @return $this
     */
    public function setExtends(string $extends = null);

    /**
     * @param string|null $implements
     * @return $this
     */
    public function setImplements(string $implements = null);

    /**
     * @param string|null $error
     * @return mixed
     */
    public function setError(string $error = null);

    /**
     * @param bool $val
     * @return IFileParseResult
     */
    public function setIsClass(bool $val);

    /**
     * @param bool $val
     * @return IFileParseResult
     */
    public function setIsAbstract(bool $val);

    /**
     * @param bool $val
     * @return IFileParseResult
     */
    public function setIsInterface(bool $val);

    /**
     * @param string $dependency
     * @return IFileParseResult
     */
    public function addUseDependency(string $dependency);

    /**
     * @param string $alias
     * @param string $value
     * @return IFileParseResult
     */
    public function addAlias(string $alias, string $value);

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
    public function getExtends();

    /**
     * @return string
     */
    public function getImplements();

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