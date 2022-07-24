<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPicker;

use Brezgalov\ComponentsAnalyser\Component\Component;

interface IComponentsPicker
{
    /**
     * @param string $componentsDir
     * @return Component[]
     */
    public function getComponentsList(string $componentsDir);
}