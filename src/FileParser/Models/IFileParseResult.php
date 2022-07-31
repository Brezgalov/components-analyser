<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\Models;

interface IFileParseResult
{
    /**
     * returns classes from use statements
     * @return string[]
     */
    public function getUseDependencies();
}