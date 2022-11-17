<?php

#parse("M2-PHP-File-Header")

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    '${Vendor}_${Namespace}',
    __DIR__
);
#[[$END$]]#
