<?php

namespace Brezgalov\ComponentsAnalyser\FileParser\CodeScaners;

use Brezgalov\ComponentsAnalyser\FileParser\Models\IFileParseResult;

class ClassScanner implements ITokenAndStringScanner
{
    /**
     * @var ITokenScanner|ClassNameScanner
     */
    public $classNameScanner;

    /**
     * @var ITokenAndStringScanner|NamespaceScanner
     */
    public $namespaceScanner;

    /**
     * @var ITokenScanner|ExtendsScanner
     */
    public $extendsScanner;

    /**
     * @var UseScanner|UseScanner
     */
    public $usesScanner;

    /**
     * @var ITokenScanner|ImplementsScanner
     */
    public $implementsScanner;

    /**
     * @var array
     */
    protected $doneParams = [];

    /**
     * @var bool
     */
    protected $parseDone = false;

    /**
     * ClassScanner constructor.
     * @param ITokenScanner|null $extendsScanner
     * @param ITokenScanner|null $implementsScanner
     * @param ITokenAndStringScanner|null $usesScanner
     * @param ITokenScanner|null $classNameScanner
     * @param ITokenAndStringScanner|null $namespaceScanner
     */
    public function __construct(
        ITokenScanner $extendsScanner = null,
        ITokenScanner $implementsScanner = null,
        ITokenAndStringScanner $usesScanner = null,
        ITokenScanner $classNameScanner = null,
        ITokenAndStringScanner $namespaceScanner = null
    ) {
        $this->extendsScanner = $extendsScanner ?: new ExtendsScanner();
        $this->implementsScanner = $implementsScanner ?: new ImplementsScanner();
        $this->usesScanner = $usesScanner ?: new UseScanner();
        $this->classNameScanner = $classNameScanner ?: new ClassNameScanner();
        $this->namespaceScanner = $namespaceScanner ?: new NamespaceScanner();
    }

    /**
     * @return array
     */
    protected function getTokenScannersMeta()
    {
        return [
            'classNameScanner',
            'extendsScanner',
            'implementsScanner',
        ];
    }

    /**
     * @return array
     */
    protected function getTokenAndStringScannersMeta()
    {
        return [
            'usesScanner',
            'namespaceScanner',
        ];
    }

    /**
     * @param string $compName
     * @return bool
     */
    protected function scannerIsDone(string $compName)
    {
        return $this->doneParams[$compName] ?? false;
    }

    /**
     * @param string $compName
     * @param bool $value
     * @return bool
     */
    protected function setScannerDone(string $compName, bool $value)
    {
        return $this->doneParams[$compName] = $value;
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
        if ($this->parseDone) {
            return IScanner::DIRECTIVE_DONE;
        }

        $parseDone = true;

        $meta = array_merge(
            $this->getTokenScannersMeta(),
            $this->getTokenAndStringScannersMeta()
        );

        foreach ($meta as $scannerComp) {
            if ($this->scannerIsDone($scannerComp)) {
                continue;
            }

            $res = $this->{$scannerComp}->passToken($tokenCode, $tokenName, $tokenVal, $fileStrNumber);

            $scannerIsDone = $res === IScanner::DIRECTIVE_DONE;

            $this->setScannerDone(
                $scannerComp,
                $scannerIsDone
            );

            $parseDone = $parseDone && $scannerIsDone;
        }

        if ($parseDone) {
            $this->parseDone = true;
        }

        return $this->parseDone ? IScanner::DIRECTIVE_DONE : IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param string $string
     * @return int
     */
    public function passString(string $string)
    {
        if ($this->parseDone) {
            return IScanner::DIRECTIVE_DONE;
        }

        $meta = $this->getTokenAndStringScannersMeta();

        /**
         * Даже если все сканеры строк скажут, что они DONE, это не означает,
         * это не значит, что вообще все сканеры DONE, поэтому не останавливаем
         * сканер класса в этом случае
         */

        foreach ($meta as $scannerComp) {
            if ($this->scannerIsDone($scannerComp)) {
                continue;
            }

            $res = $this->{$scannerComp}->passString($string);
            $this->setScannerDone(
                $scannerComp,
                $res === IScanner::DIRECTIVE_DONE
            );
        }

        return IScanner::DIRECTIVE_IN_PROGRESS;
    }

    /**
     * @param IFileParseResult $fileParseResult
     * @return IFileParseResult
     */
    public function storeScanResults(IFileParseResult $fileParseResult)
    {
        $meta = array_merge(
            $this->getTokenScannersMeta(),
            $this->getTokenAndStringScannersMeta()
        );

        foreach ($meta as $compName) {
            $fileParseResult = $this->{$compName}->storeScanResults($fileParseResult);
        }

        return $fileParseResult;
    }
}