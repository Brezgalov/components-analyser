<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class InterfaceScanner implements ITokenScanner
{
    /**
     * @var bool
     */
    public $interfaceFound = false;

    /**
     * @var bool
     */
    public $done = false;

    /**
     * @var string
     */
    public $interfaceName;

    /**
     * @param int $tokenCode
     * @param string $tokenName
     * @param string $tokenVal
     * @param int $fileStrNumber
     * @return int
     */
    public function passToken(int $tokenCode, string $tokenName, string $tokenVal, int $fileStrNumber)
    {
        if ($this->done) {
            return IScanner::DIRECTIVE_DONE;
        }

        if ($tokenName === IScanner::TOKEN_INTERFACE) {
            $this->interfaceFound = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if ($this->interfaceFound && $tokenName === IScanner::TOKEN_STRING) {
            $this->interfaceName = $tokenVal;
            $this->done = true;

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
        $fileParseResult->setIsInterface($this->interfaceFound);

        if ($this->interfaceFound) {
            $fileParseResult->setClassName($this->interfaceName);
        }

        return $fileParseResult;
    }
}