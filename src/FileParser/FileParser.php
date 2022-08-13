<?php

namespace Brezgalov\ComponentsAnalyser\FileParser;

// @todo: teach parsers to understand "require" directive
// @todo: teach parsers to search for fully qualified names inside class body
// @todo: teach parsers to determine abstract classes
// @todo: teach parsers to determine interfaces

use Brezgalov\ComponentsAnalyser\FileParser\CodeScaners\ICodeScanner;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;
use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

abstract class FileParser implements IFileParser
{
    /**
     * @return ICodeScanner[]
     */
    public abstract function getCodeScanners();

    /**
     * @param string $filePath
     * @return IFileParseResult
     */
    public function parseFile(string $filePath)
    {
        $result = new FileParseResult();

        $file = @file_get_contents($filePath);
        if ($file === false) {
            $result->error = "File \"{$filePath}\" not found or unavailable";
            return $result;
        }

        if (empty($file)) {
            return $result;
        }

        $scanners = $this->getCodeScanners();
        if (empty($scanners)) {
            return $result;
        }

        $tokens = token_get_all($file);
        $scannersDone = [];

        foreach ($tokens as $tokenInfo) {
            foreach ($scanners as $scannerId => $scanner) {
                list($tokenCode, $tokenVal, $fileStrNumber) = $tokenInfo;
                $tokenName = token_name($tokenCode);


                $resultDirective = $scanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);

                if (empty($resultDirective)) {
                    continue;
                } elseif ($resultDirective === ICodeScanner::DIRECTIVE_DONE) {
                    unset($scanners[$scannerId]);
                    $scannersDone[$scannerId] = $scanner;
                } elseif ($resultDirective === ICodeScanner::DIRECTIVE_ISSUE) {
                    $result = $scanner->storeScanResults($result);
                    return $result;
                }
            }

            if (empty($scanners)) {
                break;
            }
        }

        // some scanners may not trigger DONE, but we should call them anyway
        /** @var ICodeScanner[] $scanners */
        $scanners = array_merge($scanners, $scannersDone);
        foreach ($scanners as $scanner) {
            $result = $scanner->storeScanResults($result);
        }

        return $result;
    }
}