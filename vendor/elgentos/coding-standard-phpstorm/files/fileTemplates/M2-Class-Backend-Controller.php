<?php

#parse("M2-PHP-File-Header")

declare(strict_types=1);

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Backend\App\AbstractAction;

class ${NAME} extends AbstractAction
{
    /**
     *
     */
    public function execute()
    {
        #[[$END$]]#
    }
}