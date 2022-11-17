<?php

#parse("M2-PHP-File-Header")

declare(strict_types=1);

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Framework\App\Helper\AbstractHelper;

class ${NAME} extends AbstractHelper
{
    #[[$END$]]#
}
