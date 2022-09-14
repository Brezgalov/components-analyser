<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class UseScanner implements ITokenAndStringScanner
{
    /**
     * @var bool
     */
    protected $useStarted = false;

    /**
     * @var bool
     */
    protected $aliasStarted = false;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var string
     */
    protected $useBuilding;

    /**
     * @var array
     */
    protected $uses = [];

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @param int $tokenCode
     * @param string $tokenName
     * @param string $tokenVal
     * @param int $fileStrNumber
     * @return int
     */
    public function passToken(int $tokenCode, string $tokenName, string $tokenVal, int $fileStrNumber)
    {
        if ($tokenName === IScanner::TOKEN_USE) {
            $this->useStarted = true;
            $this->useBuilding = null;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if ($tokenName === IScanner::TOKEN_NAME_QUALIFIED) {
            if ($this->useStarted) {
                $this->useBuilding = $tokenVal;
                return IScanner::DIRECTIVE_IN_PROGRESS;
            } else {
                // @todo: namespace found somewhere across class ?
            }
        }

        if ($this->useStarted && !$this->aliasStarted && ($tokenName === IScanner::TOKEN_STRING || $tokenName === IScanner::TOKEN_NS_SEPARATOR)) {
            $this->useBuilding .= $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if ($this->useStarted && $tokenName === IScanner::TOKEN_AS) {
            $this->aliasStarted = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if ($this->useStarted && $this->aliasStarted && $tokenName === IScanner::TOKEN_STRING) {
            $this->alias = $tokenVal;
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
        if ($this->useStarted && $this->useBuilding) {
            if ($this->alias) {
                $this->aliases[$this->alias] = $this->useBuilding;
            } else {
                $this->uses[] = $this->useBuilding;
            }

            $this->alias = null;
            $this->useBuilding = null;
            $this->useStarted = false;
        }

        return IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        foreach ($this->uses as $dependency) {
            $fileParseResult->addUseDependency($dependency);
        }

        foreach ($this->aliases as $alias => $dependency) {
            $fileParseResult->addUseDependency($alias);
            $fileParseResult->addAlias($alias, $dependency);
        }

        return $fileParseResult;
    }
}