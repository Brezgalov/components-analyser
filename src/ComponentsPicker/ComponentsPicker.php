<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPicker;


use Brezgalov\ComponentsAnalyser\ComponentsPicker\Models\IComponent;

abstract class ComponentsPicker implements IComponentsPicker
{
    /**
     * @var string
     */
    protected $componentsPrefix;

    /**
     * @param string $prefix
     * @return IComponentsPicker
     */
    public function setComponentsPrefix(string $prefix)
    {
        $this->componentsPrefix = $prefix;

        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function prepareComponentName(string $name)
    {
        return "{$this->componentsPrefix}{$name}";
    }

    /**
     * @param string $componentsDir
     * @return IComponent[]
     */
    public abstract function getComponentsList(string $componentsDir);
}