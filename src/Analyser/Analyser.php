<?php

namespace Brezgalov\ComponentsAnalyser\Analyser;

use Brezgalov\ComponentsAnalyser\Analyser\Models\AnalysisResult;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\IComponentsPicker;

class Analyser
{
    /**
     * Unique File paths to scanned directories
     * Format: <path> => true - this allows easy unification on new directory attachment
     *
     * /home/user/myDir and ~/myDir equivalence not caught yet. Absolut paths expected
     * @var string[]
     */
    protected $scanDirectories = [];

    /**
     * @var IComponentsPicker
     */
    protected $componentsPicker;

    /**
     * AnalyserSetup constructor.
     * @param array|string $dir - Full path / Array of full paths to scanned directories
     * @throws \Exception
     */
    public function __construct(IComponentsPicker $picker, $dir = null)
    {
        if (!empty($dir)) {
            if (is_string($dir)) {
                $this->addScanDir($dir);
            } elseif (is_array($dir)) {
                $this->addScanDirs($dir);
            } else {
                throw new \Exception("\$dir should be either string or array");
            }
        }

        $this->componentsPicker = $picker;
    }

    /**
     * @return string[]
     */
    public function getScannedDirectories()
    {
        return array_keys($this->scanDirectories);
    }

    /**
     * @param string $dir
     * @return $this
     * @throws \Exception
     */
    public function addScanDir(string $dir)
    {
        if (!is_dir($dir)) {
            throw new \Exception("{$dir} is not a valid directory");
        }

        $this->scanDirectories[$dir] = true;

        return $this;
    }

    /**
     * @param array $directories
     * @return $this
     * @throws \Exception
     */
    public function addScanDirs(array $directories)
    {
        foreach ($directories as $directory) {
            $this->addScanDir($directory);
        }

        return $this;
    }

    /**
     * @return AnalysisResult
     */
    public function scanComponents()
    {
        $result = new AnalysisResult();

        $componentsDirs = $this->getScannedDirectories();

        foreach ($componentsDirs as $componentsDir) {

        }

        return $result;
    }
}