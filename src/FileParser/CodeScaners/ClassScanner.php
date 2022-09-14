<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ClassScanner implements ITokenAndStringScanner
{
    /**
     * @var ITokenScanner
     */
    protected $classNameScanner;

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
    protected $classNameDone = false;

    /**
     * @var bool
     */
    protected $extendsDone = false;

    /**
     * @var bool
     */
    protected $implementsDone = false;

    /**
     * @var bool
     */
    protected $parseDone = false;

    /**
     * ClassScanner constructor.
     * @param ITokenScanner|null $extendsScanner
     * @param ITokenScanner|null $implementsScanner
     * @param UseScanner|null $usesScanner
     * @param ITokenScanner|null $classNameScanner
     */
    public function __construct(
        ITokenScanner $extendsScanner = null,
        ITokenScanner $implementsScanner = null,
        UseScanner $usesScanner = null,
        ITokenScanner $classNameScanner = null
    ) {
        $this->extendsScanner = $extendsScanner ?: new ExtendsScanner();
        $this->implementsScanner = $implementsScanner ?: new ImplementsScanner();
        $this->usesScanner = $usesScanner ?: new UseScanner();
        $this->classNameScanner = $classNameScanner ?: new ClassNameScanner();
    }

    protected function getTokenScannersMeta()
    {
        return [
            'classNameDone' => 'classNameScanner',
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
        $this->usesScanner->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);

        $parseDone = true;

        $meta = $this->getTokenScannersMeta();
        foreach ($meta as $blocked => $scannerComp) {
            if ($this->{$blocked}) {
                continue;
            }

            $res = $this->{$scannerComp}->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);
            $this->{$blocked} = $res === IScanner::DIRECTIVE_DONE;

            $parseDone = $parseDone && $this->{$blocked};
        }

        $this->parseDone = $this->parseDone ?: $parseDone;

        return $this->parseDone ? IScanner::DIRECTIVE_DONE : IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param string $string
     * @return int
     */
    public function passString(string $string)
    {
        $this->usesScanner->passString($string);

        return $this->parseDone ? IScanner::DIRECTIVE_DONE : IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        $tokenScannersMeta = $this->getTokenScannersMeta();

        foreach ($tokenScannersMeta as $compName) {
            $fileParseResult = $this->{$compName}->storeScanResults($fileParseResult);
        }

        $fileParseResult = $this->usesScanner->storeScanResults($fileParseResult);

        return $fileParseResult;
    }
}