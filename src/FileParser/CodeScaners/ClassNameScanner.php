<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ClassNameScanner implements ICodeScanner
{
    const TOKEN_STRING = "T_STRING";
    const TOKEN_CLASS = "T_CLASS";

    /**
     * @var bool
     */
    protected $classTokenPassed = false;

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
        if ($tokenName === self::TOKEN_CLASS) {
            $this->classTokenPassed = true;
            return ICodeScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->className) && $this->classTokenPassed && $tokenName == self::TOKEN_STRING) {
            $this->className = $tokenVal;
            return ICodeScanner::DIRECTIVE_DONE;
        }

        return ICodeScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        $fileParseResult->setClassName($this->className);

        return $fileParseResult;
    }
}