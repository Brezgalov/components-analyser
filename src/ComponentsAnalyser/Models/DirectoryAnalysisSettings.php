<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Exceptions\InvalidDirectoryException;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\IComponentsPicker;

class DirectoryAnalysisSettings
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var IComponentsPicker
     */
    protected $componentsPicker;

    /**
     * DirectoryAnalysisSettings constructor.
     * @param string $dir
     * @param IComponentsPicker $picker
     */
    public function __construct(string $dir, IComponentsPicker $picker)
    {
        $this->directory = realpath($dir);

        if ($this->directory === false) {
            throw new InvalidDirectoryException("Directory {$dir} can not be parsed");
        }

        $this->componentsPicker = clone $picker;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return IComponentsPicker
     */
    public function getPicker()
    {
        return clone $this->componentsPicker;
    }
}