<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Patcher;

use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\FilesystemInterface;
use Youwe\CodingStandard\PhpStorm\XmlAccessorInterface;

class FileTemplatesPatcher implements ConfigPatcherInterface
{
    use CopyFilesTrait;

    /**
     * @var XmlAccessorInterface
     */
    private $xmlAccessor;

    /**
     * Constructor.
     *
     * @param XmlAccessorInterface $xmlAccessor
     */
    public function __construct(XmlAccessorInterface $xmlAccessor)
    {
        $this->xmlAccessor = $xmlAccessor;
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
        $this->copyDirectory(
            $environment->getDefaultsFilesystem(),
            $environment->getIdeConfigFilesystem(),
            'fileTemplates'
        );

        $this->patchWorkspaceConfig(
            $environment->getIdeConfigFilesystem()
        );
    }

    /**
     * Patch the workspace config.
     *
     * @param FilesystemInterface $ideConfigFs
     *
     * @return void
     */
    private function patchWorkspaceConfig(
        FilesystemInterface $ideConfigFs
    ): void {
        if (!$ideConfigFs->has('workspace.xml')) {
            return;
        }

        $xml = simplexml_load_string(
            $ideConfigFs->read('workspace.xml')
        );

        $node = $this->xmlAccessor->getDescendant(
            $xml,
            [
                ['component', ['name' => 'FileTemplateManagerImpl']],
                ['option', ['name' => 'SCHEME']]
            ]
        );

        $this->xmlAccessor->setAttributes(
            $node,
            ['value' => 'Project']
        );

        $ideConfigFs->put('workspace.xml', $xml->asXML());
    }
}
