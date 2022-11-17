<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Patcher;

use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\XmlAccessorInterface;
use SimpleXMLElement;

class InspectionsPatcher implements ConfigPatcherInterface
{
    use CopyFilesTrait;

    public const PROJECT_PHPCS      = 'phpcs.xml';
    public const INSPECTION_PROFILE = 'inspectionProfiles/MediaCT.xml';

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
            'inspectionProfiles'
        );

        $this->setProjectProfiles($environment);
    }

    /**
     * Set profiles on project level.
     *
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    private function setProjectProfiles(EnvironmentInterface $environment): void
    {
        $projectFs = $environment->getProjectFilesystem();
        if (!$projectFs->has(self::PROJECT_PHPCS)) {
            return;
        }

        $ideConfigFs = $environment->getIdeConfigFilesystem();

        $xml = simplexml_load_string(
            $ideConfigFs->read(self::INSPECTION_PROFILE)
        );

        $this->setProjectPhpCsProfile($xml);
        $ideConfigFs->put(self::INSPECTION_PROFILE, $xml->asXML());
    }

    /**
     * Set the PhpCs profile in the XML.
     *
     * @param SimpleXMLElement $xml
     *
     * @return void
     */
    private function setProjectPhpCsProfile(SimpleXMLElement $xml): void
    {
        $node = $this->xmlAccessor->getDescendant(
            $xml,
            [
                ['profile'],
                ['inspection_tool', ['class' => 'PhpCSValidationInspection']],
                ['option', ['name' => 'CUSTOM_RULESET_PATH']]
            ]
        );
        $this->xmlAccessor->setAttributes(
            $node,
            ['value' => '$PROJECT_DIR$/' . self::PROJECT_PHPCS]
        );
    }
}
