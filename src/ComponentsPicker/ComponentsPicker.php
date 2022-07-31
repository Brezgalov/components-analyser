<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPicker;


use Brezgalov\ComponentsAnalyser\ComponentsPicker\Models\IComponent;

abstract class ComponentsPicker implements IComponentsPicker
{
    /**
     * @param string $componentsDir
     * @return IComponent[]
     */
    public abstract function getComponentsList(string $componentsDir);
}