<?php

namespace Brezgalov\ComponentsAnalyser\FileParserPhp7;

use Brezgalov\ComponentsAnalyser\FileParser\FileParser;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;

class FileParserPhp7 extends FileParser
{
    const TOKEN_NS_SEPARATOR = "T_NS_SEPARATOR";
    const TOKEN_USE = "T_USE";
    const TOKEN_STRING = "T_STRING";
    const TOKEN_NAMESPACE = "T_NAMESPACE";
    const TOKEN_CLASS = "T_CLASS";

    /**
     * @return array
     */
    public function getCodeScanners()
    {
        return [

        ];
    }

//    public function parseFile(string $filePath)
//    {
//        $result = new FileParseResult();
//        $tokens = $this->tokenizeFile($filePath);
//
//        if ($tokens === false) {
//            $result->error = "File \"{$filePath}\" not found or unavailable";
//            return $result;
//        }
//
//        if (empty($tokens)) {
//            return $result;
//        }
//
//        $useToken = false;
//        $namespaceIsBuilding = false;
//        $classNamePassed = false;
//        $dependency = null;
//        foreach ($tokens as $tokenInfo) {
//            if (is_string($tokenInfo)) {
//                if ($useToken) {
//                    $result->useClasses[] = $dependency;
//
//                    $useToken = false;
//                    $dependency = null;
//                }
//
//                if ($namespaceIsBuilding) {
//                    $namespaceIsBuilding = false;
//                }
//
//                continue;
//            }
//
//            list($tokenCode, $tokenVal) = $tokenInfo;
//            $tokenName = token_name($tokenCode);
//
//            switch ($tokenName) {
//                case self::TOKEN_USE: {
//                    $useToken = true;
//                    continue 2;
//                }
//                case self::TOKEN_NAMESPACE: {
//                    $result->namespace = '';
//                    $namespaceIsBuilding = true;
//                    continue 2;
//                }
//                case self::TOKEN_CLASS: {
//                    $classNamePassed = true;
//                    continue 2;
//                }
//            }
//
//            if ($useToken && ($tokenName == self::TOKEN_STRING || $tokenName == self::TOKEN_NS_SEPARATOR)) {
//                $dependency .= $tokenVal;
//            }
//
//            if ($namespaceIsBuilding && ($tokenName == self::TOKEN_STRING || $tokenName == self::TOKEN_NS_SEPARATOR)) {
//                $result->namespace .= $tokenVal;
//            }
//
//            if ($classNamePassed && $tokenName == self::TOKEN_STRING && empty($result->className)) {
//                $result->className = $tokenVal;
//            }
//        }
//
//        return $result;
//    }
}