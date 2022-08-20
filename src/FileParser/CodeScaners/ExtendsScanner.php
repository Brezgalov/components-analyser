<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ExtendsScanner implements ITokenScanner
{
    const TOKEN_EXTENDS = "T_EXTENDS";
    const TOKEN_STRING = "T_STRING";
    const TOKEN_NAME_QUALIFIED = "T_NAME_QUALIFIED";

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
        if ($tokenName === self::TOKEN_EXTENDS) {
            $this->extendsPassed = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->done) && $this->extendsPassed && $tokenName === self::TOKEN_NAME_QUALIFIED) {
            $this->extendsVal = $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->done) && $this->extendsPassed && $tokenName === self::TOKEN_STRING) {
            $this->extendsVal .= $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        $this->done = $this->extendsVal
            && $tokenName != self::TOKEN_NAME_QUALIFIED
            && $tokenName != self::TOKEN_STRING;

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