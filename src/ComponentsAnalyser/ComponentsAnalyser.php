<?php

namespace Brezgalov\ComponentsAnalyser\ComponentsAnalyser;

use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\AnalysisDataPhpRepository;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\DirectoryAnalysisSettings;
use Brezgalov\ComponentsAnalyser\ComponentsAnalyser\Models\IAnalysisDataRepository;
use Brezgalov\ComponentsAnalyser\ComponentsPicker\IComponentsPicker;
use Brezgalov\ComponentsAnalyser\FileParser\IFileParser;

class ComponentsAnalyser
{
    /**
     * settings with component picker and unique File paths to scanned directories
     * Format: <path> => <settings object> - this allows easy unification on new directory attachment
     *
     * /home/user/myDir and ~/myDir equivalence not caught yet. Absolut paths expected
     * @var DirectoryAnalysisSettings[]
     */
    protected $directoriesSettings = [];

    /**
     * @var IFileParser
     */
    protected $fileParser;

    /**
     * @var IAnalysisDataRepository
     */
    protected $dataRepository;

    /**
     * ComponentsAnalyser constructor.
     * @param DirectoryAnalysisSettings[] $dirSettings
     * @param IFileParser $fileParser
     * @param IAnalysisDataRepository|null $dataRepository
     * @throws \Exception
     */
    public function __construct(array $dirSettings, IFileParser $fileParser, IAnalysisDataRepository $dataRepository = null)
    {
        foreach ($dirSettings as $settings) {
            $this->addDirectorySettings($settings);
        }

        $this->fileParser = $fileParser;

        $this->dataRepository = $dataRepository ?: new AnalysisDataPhpRepository();
    }

    /**
     * @param DirectoryAnalysisSettings $settings
     * @return $this
     */
    public function addDirectorySettings(DirectoryAnalysisSettings $settings)
    {
        $this->directoriesSettings[$settings->getDirectory()] = clone $settings;

        return $this;
    }

    /**
     * @return DirectoryAnalysisSettings[]
     */
    public function getDirectoriesSettings()
    {
        return array_values($this->directoriesSettings);
    }

    /**
     * @return IAnalysisDataRepository
     */
    public function scanComponents()
    {
        $dataRepository = clone $this->dataRepository;

        $settings = $this->getDirectoriesSettings();

        foreach ($settings as $directorySettings) {
            $dir = $directorySettings->getDirectory();
            $componentsPicker = $directorySettings->getPicker();

            // get components files list
            $components = $componentsPicker->getComponentsList($dir);

            foreach ($components as $component) {
                $dataRepository->addComponentDirToMap(
                    $component->getRootDirectoryPath(),
                    $component->getId()
                );

                foreach ($component->getFilesList() as $filePath) {
                    $fileParseResult = $this->fileParser->parseFile($filePath);

                    if (!$fileParseResult->getIsClass() && !$fileParseResult->getIsInterface()) {
                        continue;
                    }

                    if (empty($fileParseResult->getFullClassName())) {
                        $a = 1;
                    }

                    $dataRepository->addClassFile(
                        $fileParseResult->getFullClassName(),
                        $filePath
                    );

                    $dataRepository->addComponentOwnClass(
                        $component->getRootDirectoryPath(),
                        $fileParseResult->getFullClassName()
                    );

                    // current class uses every dependency class
                    foreach ($fileParseResult->getUseDependencies() as $dependencyClass) {
                        $dataRepository->addComponentDependency(
                            $component->getRootDirectoryPath(),
                            $fileParseResult->getFullClassName(),
                            $dependencyClass
                        );
                    }

                    // fully qualified names for implmnt and extnd clauses are not use dependencies
                    // check them out to be sure they are counted as dependency

                    $extends = $fileParseResult->getExtends();
                    $implements = $fileParseResult->getImplements();

                    if ($extends) {
                        $dataRepository->addComponentDependency(
                            $component->getRootDirectoryPath(),
                            $fileParseResult->getFullClassName(),
                            $extends
                        );
                    }

                    if ($implements) {
                        $dataRepository->addComponentDependency(
                            $component->getRootDirectoryPath(),
                            $fileParseResult->getFullClassName(),
                            $implements
                        );
                    }
                }
            }
        }

        return $dataRepository;
    }
}