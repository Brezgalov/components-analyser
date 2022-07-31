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
}