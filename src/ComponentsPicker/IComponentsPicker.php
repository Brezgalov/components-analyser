<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPicker;

use Brezgalov\ComponentsAnalyser\ComponentsPicker\Models\Component;

interface IComponentsPicker
{
    /**
     * @param string $prefix
     * @return IComponentsPicker
     */
    public function setComponentsPrefix(string $prefix);

    /**
     * @param string $componentsDir
     * @return Component[]
     */
    public function getComponentsList(string $componentsDir);
}