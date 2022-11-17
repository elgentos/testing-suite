<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Tests\Patcher;

use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\FilesystemInterface;
use Youwe\CodingStandard\PhpStorm\Patcher\InspectionsPatcher;
use Youwe\CodingStandard\PhpStorm\XmlAccessorInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_TestCase;
use SimpleXMLElement;

/**
 * @coversDefaultClass \Youwe\CodingStandard\PhpStorm\Patcher\InspectionsPatcher
 */
class InspectionsPatcherTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::patch
     * @covers ::setProjectProfiles
     */
    public function testPatch()
    {
        $accessor = $this->createMock(XmlAccessorInterface::class);

        $projectFs = $this->createMock(FilesystemInterface::class);
        $projectFs
            ->expects($this->once())
            ->method('has')
            ->with(InspectionsPatcher::PROJECT_PHPCS)
            ->willReturn(false);

        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getDefaultsFilesystem' => $defaultsFs,
                'getProjectFilesystem' => $projectFs
            ]
        );

        (new InspectionsPatcher($accessor))->patch($environment);
    }

    /**
     * @return void
     *
     * @covers ::setProjectProfiles
     * @covers ::setProjectPhpCsProfile
     */
    public function testPatchWithProjectProfile()
    {
        $child = new SimpleXMLElement('<some_data/>');

        $accessor = $this->createMock(XmlAccessorInterface::class);
        $accessor
            ->expects($this->once())
            ->method('getDescendant')
            ->with(
                $this->isInstanceOf(SimpleXMLElement::class),
                $this->isType('array')
            )
            ->willReturn($child);

        $projectFs = $this->createMock(FilesystemInterface::class);
        $projectFs
            ->expects($this->once())
            ->method('has')
            ->with(InspectionsPatcher::PROJECT_PHPCS)
            ->willReturn(true);

        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $ideConfigFs = $this->createMock(FilesystemInterface::class);
        $ideConfigFs
            ->expects($this->once())
            ->method('read')
            ->with(InspectionsPatcher::INSPECTION_PROFILE)
            ->willReturn('<some_xml/>');

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $ideConfigFs,
                'getDefaultsFilesystem' => $defaultsFs,
                'getProjectFilesystem' => $projectFs
            ]
        );
        (new InspectionsPatcher($accessor))->patch($environment);
    }
}
