<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

interface ITokenAndStringScanner extends ITokenScanner
{
    /**
     * @param string $string
     * @return int
     */
    public function passString(string $string);
}