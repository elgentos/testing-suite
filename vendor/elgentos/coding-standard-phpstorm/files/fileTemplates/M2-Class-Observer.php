<?php

#parse("M2-PHP-File-Header")

declare(strict_types=1);

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Framework\Event\ObserverInterface;

class ${NAME} implements ObserverInterface
{
    #[[$END$]]#
}
