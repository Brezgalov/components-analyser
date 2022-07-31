<?php

namespace Brezgalov\ComponentsAnalyser\FileParserPhp8;

use Brezgalov\ComponentsAnalyser\FileParser\FileParser;
use Brezgalov\ComponentsAnalyser\FileParser\Models\FileParseResult;

class FileParserPhp8 extends FileParser
{
    const TOKEN_USE = "T_USE";
    const TOKEN_CLASS_NAME = "T_NAME_QUALIFIED";

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
        foreach ($tokens as $tokenInfo) {
            if (is_string($tokenInfo)) {
                continue;
            }

            list($tokenCode, $tokenVal) = $tokenInfo;
            $tokenName = token_name($tokenCode);

            if ($tokenName === self::TOKEN_USE) {
                $useToken = true;
                continue;
            }

            if ($useToken && $tokenName === self::TOKEN_CLASS_NAME) {
                $useToken = false;
                $result->useClasses[] = $tokenVal;
            }
        }

        return $result;
    }
}