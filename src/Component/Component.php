<?php

namespace Brezgalov\ComponentsAnalyser\Component;

use Brezgalov\ComponentsAnalyser\DirectoriesScanHelper\DirectoriesScanHelper;

class Component
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $rootDirectoryPath;

    /**
     * @var array
     */
    public $files = [];

    /**
     * Component constructor.
     * @param string $id
     * @param string $fullRootPath
     */
    public function __construct(string $id, string $fullRootPath)
    {
        $this->id = $id;
        $this->rootDirectoryPath = $fullRootPath;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRootDirectoryPath()
    {
        return $this->rootDirectoryPath;
    }
}