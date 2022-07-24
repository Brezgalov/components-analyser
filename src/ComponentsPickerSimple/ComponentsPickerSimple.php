<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPickerSimple;

use Brezgalov\ComponentsAnalyser\Component\Component;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\IComponentsPicker;

class ComponentsPickerSimple implements IComponentsPicker
{
    /**
     * @param string $componentsDir
     * @return Component[]
     */
    public function getComponentsList(string $componentsDir)
    {
        $componentsFolders = $this->scanDirContents($componentsDir);

        foreach ($componentsFolders as $compName) {
            $compPath = "{$componentsDir}/{$compName}";

            if (!is_dir($compPath)) {
                continue;
            }

            $compModel = new Component($compName, $compPath);

            $components[$compName] = [
                'files' => scandir_recursive($compPath)
            ];

            foreach ($components[$compName]['files'] as $file) {
                $components[$compName]['tokens'][$file] = [];

                foreach (token_get_all(file_get_contents($file)) as $token) {
                    if (is_array($token)) {
                        $components[$compName]['tokens'][$file][] = [
                            'tokenName' => token_name($token[0]),
                            'tokenVal' => $token[1],
                            'stringNum' => $token[2],
                        ];
                    }
                }
            }

        }

        return [];
    }
}