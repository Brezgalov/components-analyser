<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class NamespaceScanner implements ITokenAndStringScanner
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var bool
     */
    protected $scanNamespace;

    /**
     * @var bool
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
        if ($this->done) {
            return IScanner::DIRECTIVE_DONE;
        }

        if ($tokenName === IScanner::TOKEN_NAMESPACE) {
            $this->scanNamespace = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if ($this->scanNamespace && $tokenName === IScanner::TOKEN_NAME_QUALIFIED) {
            $this->namespace = $tokenVal;
            $this->done = true;

            return IScanner::DIRECTIVE_DONE;
        }

        if ($this->scanNamespace && ($tokenName === IScanner::TOKEN_STRING || $tokenName === IScanner::TOKEN_NS_SEPARATOR)) {
            $this->namespace .= $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        return IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param string $string
     * @return int
     */
    public function passString(string $string)
    {
        if ($this->scanNamespace) {
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
        return $fileParseResult->setNamespace($this->namespace);
    }
}