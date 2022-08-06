<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsAnalyser;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisDataPhpRepository;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\IAnalysisDataRepository;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\IComponentsPicker;
use Brezgalov\ComponentsAnalyser\FileParser\IFileParser;

class ComponentsAnalyser
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
     * @var IFileParser
     */
    protected $fileParser;

    /**
     * @var IAnalysisDataRepository
     */
    protected $dataRepository;

    /**
     * AnalyserSetup constructor.
     * @param IComponentsPicker $picker
     * @param IFileParser $fileParser
     * @param array|string $dir - Full path / Array of full paths to scanned directories
     * @param IAnalysisDataRepository $dataRepository
     * @throws \Exception
     */
    public function __construct(IComponentsPicker $picker, IFileParser $fileParser, $dir = null, IAnalysisDataRepository $dataRepository = null)
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
        $this->fileParser = $fileParser;

        $this->dataRepository = $dataRepository ?: new AnalysisDataPhpRepository();
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
     * @return IAnalysisDataRepository
     */
    public function scanComponents()
    {
        $dataRepository = clone $this->dataRepository;

        $componentsDirs = $this->getScannedDirectories();

        foreach ($componentsDirs as $componentsDir) {
            // get components files list
            $components = $this->componentsPicker->getComponentsList($componentsDir);

            foreach ($components as $component) {
                $dataRepository->addComponentDirToMap(
                    $component->getRootDirectoryPath(),
                    $component->getId()
                );

                foreach ($component->getFilesList() as $filePath) {
                    $fileParseResult = $this->fileParser->parseFile($filePath);

                    $dataRepository->addComponentOwnClass(
                        $component->getRootDirectoryPath(),
                        $fileParseResult->getFullClassName()
                    );

                    $dataRepository->addClassFile(
                        $fileParseResult->getFullClassName(),
                        $filePath
                    );

                    foreach ($fileParseResult->getUseDependencies() as $dependencyClass) {
                        $dataRepository->addComponentDependency(
                            $component->getRootDirectoryPath(),
                            $fileParseResult->getFullClassName(),
                            $dependencyClass
                        );
                    }
                }
            }
        }

        return $dataRepository;
    }
}