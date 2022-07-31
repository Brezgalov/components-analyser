<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsPicker\Models;

interface IComponent
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getRootDirectoryPath();

    /**
     * @return string[]
     */
    public function getFilesList();
}