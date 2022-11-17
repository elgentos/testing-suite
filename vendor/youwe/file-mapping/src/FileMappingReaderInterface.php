<?php

/**
 * Copyright © Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\FileMapping;

use Iterator;

interface FileMappingReaderInterface extends Iterator
{
    /**
     * Get the current file mapping.
     *
     * @return FileMappingInterface
     */
    public function current(): FileMappingInterface;
}
