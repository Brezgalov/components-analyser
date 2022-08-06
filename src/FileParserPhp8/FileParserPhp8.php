<?php

namespace Brezgalov\ComponentsAnalyser\FileParserPhp8;

use Brezgalov\ComponentsAnalyser\FileParser\FileParser;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;

class FileParserPhp8 extends FileParser
{
    const TOKEN_USE = "T_USE";
    const TOKEN_NAME_QUALIFIED = "T_NAME_QUALIFIED";
    const TOKEN_NAMESPACE = "T_NAMESPACE";
    const TOKEN_CLASS = "T_CLASS";
    const TOKEN_STRING = "T_STRING";

    /**
     * @param string $filePath
     * @return FileParseResult
     */
    public function parseFile(string $filePath)
    {
        $result = new FileParseResult();
        $tokens = $this->tokenizeFile($filePath);

        if ($tokens === false) {
            $result->error = "File \"{$filePath}\" not found or unavailable";
            return $result;
        }

        if (empty($tokens)) {
            return $result;
        }

        $useToken = false;
        $namespacePassed = false;
        $classNamePassed = false;

        foreach ($tokens as $tokenInfo) {
            if (is_string($tokenInfo)) {
                continue;
            }

            list($tokenCode, $tokenVal) = $tokenInfo;
            $tokenName = token_name($tokenCode);

            switch ($tokenName) {
                case self::TOKEN_USE: {
                    $useToken = true;
                    continue 2;
                }
                case self::TOKEN_NAMESPACE: {
                    $namespacePassed = true;
                    continue 2;
                }
                case self::TOKEN_CLASS: {
                    $classNamePassed = true;
                    continue 2;
                }
            }

            if ($useToken && $tokenName === self::TOKEN_NAME_QUALIFIED) {
                $useToken = false;
                $result->useClasses[] = $tokenVal;
                continue;
            }

            if (
                $namespacePassed
                && empty($result->namespace)
                && $tokenName === self::TOKEN_NAME_QUALIFIED
            ) {
                $result->namespace = $tokenVal;
                continue;
            }

            if (
                $classNamePassed
                && empty($result->className)
                && $tokenName === self::TOKEN_STRING
            ) {
                $result->className = $tokenVal;
                continue;
            }
        }

        return $result;
    }
}