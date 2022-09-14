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
     * @var array
     */
    protected $aliases = [];

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
     * @param string $name
     * @return string
     */
    public function resolveLocalNamespace(string $name)
    {
        $unaliased = $this->findAlias($name);
        if ($unaliased) {
            return $unaliased;
        }

        if ($this->getNamespace() && strpos($name, "\\") === false) {
            return $this->getNamespace() . "\\{$name}";
        }

        return $name;
    }

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
     * @param string $alias
     * @param string $value
     * @return IFileParseResult
     */
    public function addAlias(string $alias, string $value)
    {
        $this->aliases[$alias] = $value;

        return $this;
    }

    /**
     * @param string $alias
     * @return string|null
     */
    public function findAlias(string $alias)
    {
        return $this->aliases[$alias] ?? null;
    }

    /**
     * @return string[]
     */
    public function getUseDependencies()
    {
        $uses = array_values($this->useClasses);

        foreach ($uses as &$dependency) {
            if (array_key_exists($dependency, $this->aliases)) {
                $dependency = $this->aliases[$dependency];
            }
        }

        return $uses;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string|null
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string|null
     */
    public function getExtends()
    {
        return $this->extends ? $this->resolveLocalNamespace($this->extends) : null;
    }

    /**
     * @return string|null
     */
    public function getImplements()
    {
        return $this->implements ? $this->resolveLocalNamespace($this->implements) : null;
    }

    /**
     * @return string|null
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