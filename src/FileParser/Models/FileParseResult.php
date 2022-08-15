<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\Models;

class FileParseResult implements IFileParseResult
{
    /**
     * @var string|null
     */
    protected $error;

    /**
     * @var string[]
     */
    protected $useClasses = [];

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var bool
     */
    protected $isClass = false;

    /**
     * @var bool
     */
    protected $isAbstract = false;

    /**
     * @var bool
     */
    protected $isInterface = false;

    /**
     * @param string $val
     */
    public function setClassName(string $val)
    {
        $this->className = $val;
    }

    /**
     * @param string $val
     */
    public function setNamespace(string $val)
    {
        $this->namespace = $val;
    }

    /**
     * @param bool $val
     */
    public function setIsClass(bool $val)
    {
        $this->isClass = $val;
    }

    /**
     * @param string $error
     */
    public function setError(string $error)
    {
        $this->error = $error;
    }

    /**
     * @param bool $val
     */
    public function setIsAbstract(bool $val)
    {
        $this->isAbstract = $val;
    }

    /**
     * @param bool $val
     */
    public function setIsInterface(bool $val)
    {
        $this->isInterface = $val;
    }

    /**
     * @param string $dependency
     */
    public function addUseDependency(string $dependency)
    {
        $this->useClasses[$dependency] = $dependency;
    }

    /**
     * @return string[]
     */
    public function getUseDependencies()
    {
        return array_values($this->useClasses);
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getFullClassName()
    {
        return $this->className ? "{$this->namespace}\\{$this->className}" : null;
    }

    /**
     * @return bool
     */
    public function getIsClass()
    {
        return $this->isClass;
    }

    /**
     * @return bool
     */
    public function getIsAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * @return bool
     */
    public function getIsInterface()
    {
        return $this->isInterface;
    }
}