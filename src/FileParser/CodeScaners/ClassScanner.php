<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ClassScanner implements ITokenScanner, IStringScanner
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
     * @var ITokenScanner
     */
    protected $extendsScanner;

    /**
     * @var UseScanner
     */
    protected $usesScanner;

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

    /**
     * ClassScanner constructor.
     * @param ITokenScanner|null $extendsScanner
     * @param ITokenScanner|null $implementsScanner
     * @param ITokenScanner|null $usesScanner
     */
    public function __construct(
        ITokenScanner $extendsScanner = null,
        ITokenScanner $implementsScanner = null,
        UseScanner $usesScanner = null
    ) {
        $this->extendsScanner = $extendsScanner ?: new ExtendsScanner();
        $this->implementsScanner = $implementsScanner ?: new ImplementsScanner();
        $this->usesScanner = $usesScanner ?: new UseScanner();
    }

    protected function getTokenScannersMeta()
    {
        return [
            // @todo: add class name scanner
            // @todo: add namespace scanner
            'extendsDone' => 'extendsScanner',
            'implementsDone' => 'implementsScanner',
        ];
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
        if ($tokenName === IScanner::TOKEN_CLASS) {
            $this->isClass = true;
            return IScanner::DIRECTIVE_IN_PROGRESS;
        }

        if (empty($this->className) && $this->isClass && $tokenName == IScanner::TOKEN_STRING) {
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

        $this->usesScanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);

        return IScanner::DIRECTIVE_IN_PROGRESS;
    }

    public function passString(string $string)
    {
        $this->usesScanner->passString($string);
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        $fileParseResult = $this->extendsScanner->storeScanResults($fileParseResult);
        $fileParseResult = $this->implementsScanner->storeScanResults($fileParseResult);
        $fileParseResult = $this->usesScanner->storeScanResults($fileParseResult);

        return $fileParseResult
            ->setIsClass($this->isClass)
            ->setClassName($this->className);
    }
}