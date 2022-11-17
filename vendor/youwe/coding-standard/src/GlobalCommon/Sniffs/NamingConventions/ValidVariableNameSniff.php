<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\GlobalCommon\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ValidVariableNameSniff implements Sniff
{
    /** @var array  */
    public $allowedNames = [
        '_GET',
        '_POST',
        '_COOKIE',
        '_FILES',
        '_REQUEST',
        '_SERVER',
        '_SESSION'
    ];

    /**
     * Listen to variable name tokens.
     *
     * @return int[]
     */
    public function register()
    {
        return [T_VARIABLE];
    }

    /**
     * Check variable names to make sure no underscores are used.
     *
     * @param File $phpcsFile
     * @param int  $stackPtr
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        if (
            !in_array($varName, $this->allowedNames)
            && preg_match('/^_/', $varName)
        ) {
            $phpcsFile->addWarning(
                'Variable names may not start with an underscore',
                $stackPtr,
                'IllegalVariableNameUnderscore'
            );
        }
    }
}
