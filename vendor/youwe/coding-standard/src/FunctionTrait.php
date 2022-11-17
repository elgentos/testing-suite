<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard;

use PHP_CodeSniffer\Files\File;

trait FunctionTrait
{
    /**
     * Get a function name.
     *
     * @param File $file
     * @param int  $functionIndex
     *
     * @return bool|string
     */
    protected function getFunctionName(File $file, $functionIndex)
    {
        $index = $this->findFunctionNameIndex($file, $functionIndex);
        return $index
            ? $file->getTokens()[$index]['content']
            : false;
    }

    /**
     * Find the function name index.
     *
     * @param File $file
     * @param int  $functionIndex
     *
     * @return bool|int
     */
    protected function findFunctionNameIndex(File $file, $functionIndex)
    {
        return $file->findNext([T_STRING], $functionIndex);
    }

    /**
     * Find the start of a function body.
     *
     * @param File $file
     * @param int  $functionIndex
     *
     * @return bool|int
     */
    protected function findFunctionBodyStartIndex(File $file, $functionIndex)
    {
        return $file->findNext([T_SEMICOLON, T_OPEN_CURLY_BRACKET], $functionIndex);
    }
}
