<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ClassNameScanner implements ITokenScanner
{
    /**
     * @var bool
     */
    protected $isClass = false;

    /**
     * @var string
     */
    protected $className;

    /**
     * @param int $tokenCode
     * @param string $tokenName
     * @param string $tokenVal
     * @param int $fileStrNumber
     * @return int
     */
    public function passToken(int $tokenCode, string $tokenName, string $tokenVal, int $fileStrNumber)
    {
        if ($tokenName === IScanner::TOKEN_CLASS) {
            $this->isClass = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->className) && $this->isClass && $tokenName == IScanner::TOKEN_STRING) {
            $this->className = $tokenVal;
            return IScanner::DIRECTIVE_DONE;
        }

        return IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        return $fileParseResult
            ->setIsClass($this->isClass)
            ->setClassName($this->className);
    }
}