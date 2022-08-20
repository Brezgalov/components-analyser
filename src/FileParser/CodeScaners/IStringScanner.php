<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

interface IStringScanner extends IScanner
{
    public function passString(string $string);
}