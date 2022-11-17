<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\GlobalCommon\Sniffs\Php7;

use Youwe\CodingStandard\FunctionTrait;
use Youwe\CodingStandard\PhpDocCommentTrait;
use Youwe\CodingStandard\PhpVersionTrait;
use Youwe\CodingStandard\TypeHintsTrait;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ReturnTypeSniff implements Sniff
{
    use PhpDocCommentTrait;
    use PhpVersionTrait;
    use TypeHintsTrait;
    use FunctionTrait;

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
        if ($this->getPhpVersion() < 70000) {
            return;
        }

        $functionStart = $stackPtr;
        $commentEnd    = $this->findCommentEndIndex($file, $stackPtr);
        $commentStart  = $this->findCommentStartIndex($file, $commentEnd);

        if (is_int($commentStart) && is_int($commentEnd)) {
            $suggestedReturnTypes = $this->findSuggestedReturnTypes($file, $commentStart);
            $returnType           = $this->findActualReturnType($file, $functionStart);

            $this->validateMultipleReturnTypes($file, $functionStart, $returnType, $suggestedReturnTypes);
            $this->validateReturnTypeNotEmpty($file, $functionStart, $returnType, $suggestedReturnTypes);
            $this->validateReturnTypeValue($file, $functionStart, $returnType, $suggestedReturnTypes);
        }
    }

    /**
     * @param File   $file
     * @param int    $functionStart
     * @param string $returnType
     * @param array  $suggestedReturnTypes
     *
     * @return void
     */
    protected function validateMultipleReturnTypes(
        File $file,
        $functionStart,
        $returnType,
        array $suggestedReturnTypes
    ) {
        if (
            empty($returnType)
            && count($suggestedReturnTypes) > 1
        ) {
            $file->addWarning(
                'Multiple return types are discouraged',
                $functionStart,
                'MultipleReturnTypes',
                [],
                3
            );
        }
    }

    /**
     * @param File   $file
     * @param int    $functionStart
     * @param string $returnType
     * @param array  $suggestedReturnTypes
     *
     * @return void
     */
    protected function validateReturnTypeNotEmpty(
        File $file,
        $functionStart,
        $returnType,
        array $suggestedReturnTypes
    ) {
        $filteredReturnTypes = array_filter($suggestedReturnTypes);
        if (
            empty($returnType)
            && count($suggestedReturnTypes) == 1
            && count($filteredReturnTypes) == 1
        ) {
            $file->addWarning(
                sprintf(
                    'Method should have a return type in PHP7, use %s',
                    reset($filteredReturnTypes)
                ),
                $functionStart,
                'MissingReturnType'
            );
        }
    }

    /**
     * @param File   $file
     * @param int    $functionStart
     * @param string $returnType
     * @param array  $suggestedReturnTypes
     *
     * @return void
     */
    protected function validateReturnTypeValue(
        File $file,
        $functionStart,
        $returnType,
        array $suggestedReturnTypes
    ) {
        $filteredReturnTypes = array_filter($suggestedReturnTypes);
        if (
            !empty($returnType)
            && count($filteredReturnTypes)
            && !in_array($returnType, $filteredReturnTypes)
        ) {
            $file->addWarning(
                sprintf(
                    'Method return type should be one of (%s)',
                    implode('|', $suggestedReturnTypes)
                ),
                $functionStart,
                'WrongReturnType'
            );
        }
    }

    /**
     * @param File $file
     * @param int  $commentStart
     *
     * @return array
     */
    protected function findSuggestedReturnTypes(File $file, $commentStart)
    {
        $returnTag = $this->findSingleCommentTagIndex($file, $commentStart, '@return');
        return $returnTag
            ? $this->getSuggestedTypes($this->getTypeFromTag($file, $returnTag))
            : [];
    }

    /**
     * @param File   $file
     * @param string $functionStart
     *
     * @return string
     */
    protected function findActualReturnType(
        File $file,
        $functionStart
    ) {
        $properties = $file->getMethodProperties($functionStart);
        return ltrim($properties['return_type'], '?');
    }
}
