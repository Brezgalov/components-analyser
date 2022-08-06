<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\Models;

interface IFileParseResult
{
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
    public function getFullClassName();
}