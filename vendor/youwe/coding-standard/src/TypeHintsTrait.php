<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Common;

trait TypeHintsTrait
{
    /**
     * Gets the type from a tag.
     *
     * @param File $file
     * @param int  $tagIndex
     *
     * @return string
     */
    protected function getTypeFromTag(File $file, $tagIndex)
    {
        $content = $file->getTokens()[($tagIndex + 2)]['content'];
        $parts   = explode(' ', $content, 2);
        $type    = $parts[0];
        return $type;
    }

    /**
     * Gets the suggested types based on a PHPDoc type.
     *
     * @param string $type
     *
     * @return array
     */
    protected function getSuggestedTypes($type)
    {
        $mapping = [
            'void'    => false,
            'mixed'   => false,
            'null'    => false,
            'int'     => 'int',
            'integer' => 'int',
            'string'  => 'string',
            'float'   => 'float',
            'bool'    => 'bool',
            'boolean' => 'bool',
        ];

        $typeNames = explode('|', $type);
        return array_unique(
            array_map(
                function ($typeName) use ($mapping) {
                    if (isset($mapping[$typeName])) {
                        return $mapping[$typeName];
                    }

                    if ($this->isTypeArray($typeName)) {
                        return 'array';
                    }

                    if ($this->isTypeCallable($typeName)) {
                        return 'callable';
                    }

                    if ($this->isTypeObject($typeName)) {
                        return $typeName;
                    }

                    return false;
                },
                $typeNames
            )
        );
    }

    /**
     * @param string $typeName
     *
     * @return bool
     */
    protected function isTypeArray($typeName)
    {
        return strpos($typeName, 'array') !== false
            || substr($typeName, -2) === '[]';
    }

    /**
     * @param string $typeName
     *
     * @return bool
     */
    protected function isTypeCallable($typeName)
    {
        return strpos($typeName, 'callable') !== false
            || strpos($typeName, 'callback') !== false;
    }

    /**
     * @param string $typeName
     *
     * @return bool
     */
    protected function isTypeObject($typeName)
    {
        return in_array($typeName, Common::$allowedTypes) === false;
    }
}
