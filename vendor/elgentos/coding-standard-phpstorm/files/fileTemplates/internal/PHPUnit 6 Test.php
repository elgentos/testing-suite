<?php

#parse("PHP File Header")

declare(strict_types=1);

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use PHPUnit\Framework\TestCase;
use ${TESTED_NAMESPACE}\\${TESTED_NAME};

/**
 * @coversDefaultClass \\${TESTED_NAMESPACE}\\${TESTED_NAME}
 */
class ${NAME} extends TestCase
{
}
