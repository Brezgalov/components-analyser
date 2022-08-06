<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\Models;

class FileParseResult implements IFileParseResult
{
    /**
     * @var string|null
     */
    public $error;

    /**
     * @var string[]
     */
    public $useClasses = [];

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $className;

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
    public function getFullClassName()
    {
        return $this->className ? "{$this->namespace}\\{$this->className}" : null;
    }
}