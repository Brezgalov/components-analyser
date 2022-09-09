<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

interface IStringScanner extends IScanner
{
    /**
     * @param string $string
     * @return int
     */
    public function passString(string $string);
}