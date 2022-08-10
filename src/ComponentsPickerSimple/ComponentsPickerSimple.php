<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPickerSimple;

use Brezgalov\ComponentsAnalyser\ComponentsPicker\ComponentsPicker;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\Models\Component;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\IComponentsPicker;
use Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\DirectoriesScanHelper;
use Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\MaxDeepOverflowException;

class ComponentsPickerSimple extends ComponentsPicker implements IComponentsPicker
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
     * @return Component[]|false
     * @throws MaxDeepOverflowException
     */
    public function getComponentsList(string $componentsDir)
    {
        $componentsFolders = $this->dirHelper->scanDirContents($componentsDir);

        if (empty($componentsFolders)) {
            return $componentsFolders;
        }

        $components = [];
        foreach ($componentsFolders as $compDirectoryName) {
            $compName = $this->prepareComponentName($compDirectoryName);
            $compPath = "{$componentsDir}/{$compDirectoryName}";

            $compModel = new Component($compName, $compPath);
            $compModel->files = $this->dirHelper->getDirectoryFiles($compPath);

            $components[$compName] = $compModel;
        }

        return $components;
    }
}