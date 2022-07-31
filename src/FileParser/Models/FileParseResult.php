<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\Models;

class FileParseResult implements IFileParseResult
{
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
}