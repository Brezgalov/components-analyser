<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ExtendsScanner implements ITokenScanner
{
    /**
     * @var bool
     */
    protected $extendsPassed = false;

    /**
     * @var string
     */
    protected $extendsVal;

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
        if ($tokenName === IScanner::TOKEN_EXTENDS) {
            $this->extendsPassed = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->done) && $this->extendsPassed && $tokenName === IScanner::TOKEN_NAME_QUALIFIED) {
            $this->extendsVal = $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->done) && $this->extendsPassed && ($tokenName === IScanner::TOKEN_STRING || $tokenName === IScanner::TOKEN_NS_SEPARATOR)) {
            $this->extendsVal .= $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        $this->done = $this->extendsVal
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
        return $fileParseResult->setExtends($this->extendsVal);
    }
}