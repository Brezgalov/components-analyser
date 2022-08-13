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

    public function setIsAbstract(bool $val)
    {
        // TODO: Implement setIsAbstract() method.
    }

    public function setIsInterface(bool $val)
    {
        // TODO: Implement setIsInterface() method.
    }

    /**
     * @return string[]
     */
    public function getUseDependencies()
    {
        return $this->useClasses;
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
    public function isClass()
    {
        return $this->isClass;
    }
}