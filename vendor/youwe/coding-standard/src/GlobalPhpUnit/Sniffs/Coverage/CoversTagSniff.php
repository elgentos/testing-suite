<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\GlobalPhpUnit\Sniffs\Coverage;

use Youwe\CodingStandard\FunctionTrait;
use Youwe\CodingStandard\PhpDocCommentTrait;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class CoversTagSniff implements Sniff
{
    use FunctionTrait;
    use PhpDocCommentTrait;

    /**
     * This is public so it can be configured in a rule set.
     *
     * @var array
     */
    public $methodPatterns = [
        'test*'
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_FUNCTION];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param File $file
     * @param int  $stackPtr
     *
     * @return void
     */
    public function process(File $file, $stackPtr)
    {
        if (!$this->matchesPatterns($this->getFunctionName($file, $stackPtr))) {
            return;
        }

        $commentEnd   = $this->findCommentEndIndex($file, $stackPtr);
        $commentStart = $this->findCommentStartIndex($file, $commentEnd);
        if (
            $commentStart
            && $commentEnd
            && !$this->hasCoversNothingTag($file, $commentStart)
        ) {
            $coversTags = $this->getCoversTags($file, $commentStart);

            $this->validateCoversTagExists($file, $commentStart, $coversTags);
            $this->validateCoversTagsNotEmpty($file, $coversTags);
            $this->validateCoversTagsAreRelative($file, $coversTags);
        }
    }

    /**
     * @param File  $file
     * @param int   $commentStart
     * @param array $tags
     *
     * @return void
     */
    protected function validateCoversTagExists(File $file, $commentStart, array $tags)
    {
        if (empty($tags)) {
            $file->addError(
                'Test methods must include a @covers tag',
                $commentStart,
                'CoversTagMissing'
            );
        }
    }

    /**
     * @param File  $file
     * @param array $tags
     *
     * @return void
     */
    protected function validateCoversTagsNotEmpty(File $file, array $tags)
    {
        foreach ($tags as $index => $value) {
            if (empty($value)) {
                $file->addError(
                    'Covers tag must not be empty',
                    $index,
                    'CoversTagEmpty'
                );
            }
        }
    }

    /**
     * @param File  $file
     * @param array $tags
     *
     * @return void
     */
    protected function validateCoversTagsAreRelative(File $file, array $tags)
    {
        foreach ($tags as $index => $value) {
            if (!empty($value) && substr($value, 0, 2) !== '::') {
                $file->addWarning(
                    'Covers tag should start with ::',
                    $index,
                    'CoversTagAbsolute',
                    [],
                    3
                );
            }
        }
    }

    /**
     * @param File $file
     * @param int  $commentStart
     *
     * @return string[]
     */
    protected function getCoversTags(File $file, $commentStart)
    {
        $tags = [];

        $tokens  = $file->getTokens();
        $indexes = $this->findCommentTagIndexes($file, $commentStart, '@covers');
        foreach ($indexes as $index) {
            $tags[$index] = isset($tokens[$index + 2]) && $tokens[$index + 2]['code'] == T_DOC_COMMENT_STRING
                ? $tokens[$index + 2]['content']
                : null;
        }

        return $tags;
    }

    /**
     * @param File $file
     * @param int  $commentStart
     *
     * @return bool
     */
    protected function hasCoversNothingTag(File $file, $commentStart)
    {
        return count($this->findCommentTagIndexes($file, $commentStart, '@coversNothing'))
            > 0;
    }

    /**
     * @param string $functionName
     *
     * @return bool
     */
    protected function matchesPatterns($functionName)
    {
        $matches = array_filter(
            array_map(
                function ($pattern) use ($functionName) {
                    return fnmatch($pattern, $functionName);
                },
                $this->methodPatterns
            )
        );
        return !empty($matches);
    }
}
