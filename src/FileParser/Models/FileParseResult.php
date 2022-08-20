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
     * @var string
     */
    protected $extends;

    /**
     * @var string
     */
    protected $implements;

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
     * @param string|null $val
     * @return IFileParseResult
     */
    public function setClassName(string $val = null)
    {
        $this->className = $val;

        return $this;
    }

    /**
     * @param string|null $val
     * @return IFileParseResult
     */
    public function setNamespace(string $val = null)
    {
        $this->namespace = $val;

        return $this;
    }

    /**
     * @param string|null $extends
     * @return IFileParseResult
     */
    public function setExtends(string $extends = null)
    {
        $this->extends = $extends;

        return $this;
    }

    /**
     * @param string|null $implements
     * @return IFileParseResult
     */
    public function setImplements(string $implements = null)
    {
        $this->implements = $implements;

        return $this;
    }

    /**
     * @param string|null $error
     * @return IFileParseResult
     */
    public function setError(string $error = null)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @param bool $val
     * @return IFileParseResult
     */
    public function setIsClass(bool $val)
    {
        $this->isClass = $val;

        return $this;
    }

    /**
     * @param bool $val
     * @return IFileParseResult
     */
    public function setIsAbstract(bool $val)
    {
        $this->isAbstract = $val;

        return $this;
    }

    /**
     * @param bool $val
     * @return IFileParseResult
     */
    public function setIsInterface(bool $val)
    {
        $this->isInterface = $val;

        return $this;
    }

    /**
     * @param string $dependency
     * @return IFileParseResult
     */
    public function addUseDependency(string $dependency)
    {
        $this->useClasses[$dependency] = $dependency;

        return $this;
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
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * @return string
     */
    public function getImplements()
    {
        return $this->implements;
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