<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard;

use PHP_CodeSniffer\Config;

trait PhpVersionTrait
{
    /**
     * Gets the PHP version.
     *
     * @return int
     */
    protected function getPhpVersion()
    {
        return Config::getConfigData('php_version')
            ?: PHP_VERSION_ID;
    }
}
