<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPicker\Models;

class Component implements IComponent
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
     * @var string[]
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

    /**
     * @return string[]
     */
    public function getFilesList()
    {
        return $this->files;
    }
}