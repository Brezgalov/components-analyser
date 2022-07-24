<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPicker;

use Brezgalov\ComponentsAnalyser\Component\Component;

abstract class ComponentsPicker implements IComponentsPicker
{


    /**
     * @param string $componentsDir
     * @return Component[]
     */
    public abstract function getComponentsList(string $componentsDir);
}