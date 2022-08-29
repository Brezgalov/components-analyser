<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ImplementsScanner implements ITokenScanner
{
    /**
     * @var bool
     */
    protected $implementsPassed = false;

    /**
     * @var string
     */
    protected $implementsVal;

    /**
     * @var
     */
    protected $done;

    /**
     * @param int $tokenCode
     * @param string $tokenName
     * @param string $tokenVal
     * @param int $fileStrNumber
     * @return int
     */
    public function passToken(int $tokenCode, string $tokenName, string $tokenVal, int $fileStrNumber)
    {
        if ($tokenName === IScanner::TOKEN_IMPLEMENTS) {
            $this->implementsPassed = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->done) && $this->implementsPassed && $tokenName === IScanner::TOKEN_NAME_QUALIFIED) {
            $this->implementsVal = $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->done) && $this->implementsPassed && ($tokenName === IScanner::TOKEN_STRING || $tokenName === IScanner::TOKEN_NS_SEPARATOR)) {
            $this->implementsVal .= $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        $this->done = $this->implementsVal
            && $tokenName != IScanner::TOKEN_NAME_QUALIFIED
            && $tokenName != IScanner::TOKEN_STRING;

        return $this->done ? IScanner::DIRECTIVE_DONE : IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        return $fileParseResult->setImplements($this->implementsVal);
    }
}