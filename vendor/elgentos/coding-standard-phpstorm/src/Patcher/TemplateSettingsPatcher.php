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

class TemplateSettingsPatcher implements ConfigPatcherInterface
{
    use CopyFilesTrait;

    /**
     * @var XmlAccessorInterface
     */
    private $xmlAccessor;

    /**
     * @var array
     */
    private $includePaths = [];

    /**
     * Constructor.
     *
     * @param XmlAccessorInterface $xmlAccessor
     */
    public function __construct(XmlAccessorInterface $xmlAccessor)
    {
        $this->xmlAccessor  = $xmlAccessor;
        $this->includePaths = [
            'M2-PHP-File-Header.php',
            'M2-Settings.php',
            'M2-XML-File-Header.xml'
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
        $this->patchFileTemplateSettings(
            $environment
        );
        $this->patchIncludes(
            $environment
        );
    }

    /**
     * Patch file template settings if exists otherwise create one.
     *
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    public function patchFileTemplateSettings(
        EnvironmentInterface $environment
    ): void {
        if (!$environment->getIdeConfigFilesystem()->has('file.template.settings.xml')) {
            $this->copyFile(
                $environment->getDefaultsFilesystem(),
                $environment->getIdeConfigFilesystem(),
                'file.template.settings.xml'
            );
        } else {
            $xml = simplexml_load_string(
                $environment->getIdeConfigFilesystem()->read('file.template.settings.xml')
            );

            foreach ($this->getFileTemplates() as $xmlTag => $fileTemplateNames) {
                foreach ($fileTemplateNames as $fileTemplateName) {
                    $node = $this->xmlAccessor->getDescendant(
                        $xml,
                        [
                            [
                                'component',
                                ['name' => 'ExportableFileTemplateSettings']
                            ],
                            [$xmlTag],
                            ['template', ['name' => $fileTemplateName]]
                        ]
                    );
                    $this->xmlAccessor->setAttributes(
                        $node,
                        [
                            'reformat' => 'false',
                            'live-template-enabled' => 'true'
                        ]
                    );
                    $environment->getIdeConfigFilesystem()->put('file.template.settings.xml', $xml->asXML());
                }
            }
        }
    }

    /**
     * Add copyright files to project if they do not exist
     *
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    public function patchIncludes(EnvironmentInterface $environment): void
    {
        foreach ($this->includePaths as $fileName) {
            if (!$environment->getIdeConfigFilesystem()->has("fileTemplates/includes/$fileName")) {
                $environment->getIdeConfigFilesystem()->put(
                    "fileTemplates/includes/$fileName",
                    $environment->getDefaultsFilesystem()->read("includes/$fileName")
                );
            }
        }
    }

    /**
     * Enable file templates
     *
     * @return array
     */
    public function getFileTemplates(): array
    {
        return [
            'default_templates' => [
                'M2-Acl XML.xml',
                'M2-Class.php',
                'M2-Class-Backend-Controller',
                'M2-Class-Block.php',
                'M2-Class-Helper.php',
                'M2-Class-Observer.php',
                'M2-Class-ViewModel.php',
                'M2-Config-XML.xml',
                'M2-Db-schema-XML.xml',
                'M2-DI.xml',
                'M2-Events.xml',
                'M2-Extension-Attributes-XML.xml',
                'M2-Layout-XML.xml',
                'M2-Menu.xml',
                'M2-Module-XML.xml',
                'M2-Registration.php',
                'M2-Routes.xml',
                'M2-Sales-XML.xml',
                'M2-System-include-XML.xml',
                'M2-System-XML.xml'
            ],
            'includes_templates' => [
                'M2-PHP-File-Header.php',
                'M2-Settings.php',
                'M2-XML-File-Header.xml',
            ]
        ];
    }
}
