<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPickerSimple;

use Brezgalov\ComponentsAnalyser\Component\Component;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\IComponentsPicker;
use Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\DirectoriesScanHelper;

class ComponentsPickerSimple implements IComponentsPicker
{
    /**
     * @var DirectoriesScanHelper
     */
    public $dirHelper;

    /**
     * ComponentsPickerSimple constructor.
     */
    public function __construct()
    {
        $this->dirHelper = new DirectoriesScanHelper();
    }

    /**
     * @param string $componentsDir
     * @return Component[]
     */
    public function getComponentsList(string $componentsDir)
    {
        $componentsFolders = $this->dirHelper->scanDirContents($componentsDir);

        if (empty($componentsFolders)) {
            return $componentsFolders;
        }

        $components = [];
        foreach ($componentsFolders as $compName) {
            $compPath = "{$componentsDir}/{$compName}";

            $compModel = new Component($compName, $compPath);
            $compModel->files = $this->dirHelper->getDirectoryFiles($compPath);

            $components[$compName] = $compModel;
        }

        return $components;
    }
}