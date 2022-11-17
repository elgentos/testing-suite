<?php

#parse("M2-PHP-File-Header")

declare(strict_types=1);

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Framework\View\Element\Block\ArgumentInterface;

class ${NAME} implements ArgumentInterface
{
    #[[$END$]]#
}
