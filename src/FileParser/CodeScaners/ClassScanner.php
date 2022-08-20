<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ClassScanner implements ITokenScanner
{
    const TOKEN_STRING = "T_STRING";
    const TOKEN_CLASS = "T_CLASS";
    const TOKEN_IMPLEMENTS = "T_IMPLEMENTS";

    /**
     * @var bool
     */
    protected $isClass = false;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var ITokenScanner
     */
    protected $extendsScanner;

    /**
     * @var ITokenScanner
     */
    protected $implementsScanner;

    /**
     * @var bool
     */
    protected $extendsDone = false;

    /**
     * @var bool
     */
    protected $implementsDone = false;

    public function __construct(
        ITokenScanner $extendsScanner = null,
        ITokenScanner $implementsScanner = null,
        ITokenScanner $usesScanner = null
    ) {
        $this->extendsScanner = $extendsScanner ?: new ExtendsScanner();
        $this->implementsScanner = $implementsScanner ?: new ImplementsScanner();
    }

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
            $this->isClass = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->className) && $this->isClass && $tokenName == self::TOKEN_STRING) {
            $this->className = $tokenVal;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (!$this->extendsDone) {
            $res = $this->extendsScanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);

            $this->extendsDone = $res === IScanner::DIRECTIVE_DONE;
        }

        if (!$this->implementsDone) {
            $res = $this->implementsScanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);

            $this->implementsDone = $res === IScanner::DIRECTIVE_DONE;
        }

        return IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        $fileParseResult = $this->extendsScanner->storeScanResults($fileParseResult);
        $fileParseResult = $this->implementsScanner->storeScanResults($fileParseResult);

        return $fileParseResult
            ->setIsClass($this->isClass)
            ->setClassName($this->className);
    }
}