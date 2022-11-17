<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

trait PhpDocCommentTrait
{
    /**
     * Gets the end of a PHPDoc comment that is directly above an element.
     *
     * @param File $file
     * @param int  $elementIndex
     *
     * @return bool|int
     */
    protected function findCommentEndIndex(File $file, $elementIndex)
    {
        $searchTypes   = array_merge(Tokens::$methodPrefixes, [T_WHITESPACE]);
        $previousToken = $file->findPrevious($searchTypes, $elementIndex - 1, null, true);
        if (
            $previousToken
            && $file->getTokens()[$previousToken]['code'] == T_DOC_COMMENT_CLOSE_TAG
        ) {
            return $previousToken;
        }

        return false;
    }

    /**
     * Gets the start of a PHPDoc comment based on the end index.
     *
     * @param File $file
     * @param int  $commentEnd
     *
     * @return bool|int
     */
    protected function findCommentStartIndex(File $file, $commentEnd)
    {
        if (!$commentEnd) {
            return false;
        }

        return $file->getTokens()[$commentEnd]['comment_opener'];
    }


    /**
     * Gets the index of a PHPDoc tag.
     *
     * @param File   $file
     * @param int    $commentStart
     * @param string $tagName
     *
     * @return bool|int
     */
    protected function findSingleCommentTagIndex(File $file, $commentStart, $tagName)
    {
        $indexes = $this->findCommentTagIndexes($file, $commentStart, $tagName);
        return count($indexes)
            ? array_shift($indexes)
            : false;
    }

    /**
     * Gets the indexes of a PHPDoc tag.
     *
     * @param File   $file
     * @param int    $commentStart
     * @param string $tagName
     *
     * @return array
     */
    protected function findCommentTagIndexes(File $file, $commentStart, $tagName)
    {
        $indexes = [];
        $tokens  = $file->getTokens();

        foreach ($tokens[$commentStart]['comment_tags'] as $index) {
            if ($tokens[$index]['content'] === $tagName) {
                $indexes[] = $index;
            }
        }

        return $indexes;
    }
}
