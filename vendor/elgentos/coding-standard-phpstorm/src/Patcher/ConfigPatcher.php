<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Patcher;

use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\XmlAccessor;

class ConfigPatcher implements ConfigPatcherInterface
{
    /**
     * @var ConfigPatcherInterface[]
     */
    private $patchers;

    /**
     * Constructor.
     *
     * @param array $patchers
     */
    public function __construct(array $patchers = null)
    {
        $xmlAccessor = new XmlAccessor();

        $this->patchers = $patchers !== null
            ? $patchers
            : [
                new CodeStylePatcher(),
                new FileTemplatesPatcher($xmlAccessor),
                new InspectionsPatcher($xmlAccessor),
                new TemplateSettingsPatcher($xmlAccessor),
                new LiveTemplatesPatcher()
            ];
    }

    /**
     * Patch the config.
     *
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    public function patch(
        EnvironmentInterface $environment
    ): void {
        foreach ($this->patchers as $patcher) {
            $patcher->patch($environment);
        }
    }
}
