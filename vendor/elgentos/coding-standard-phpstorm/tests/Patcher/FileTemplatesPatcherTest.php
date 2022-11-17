<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Tests\Patcher;

use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\FilesystemInterface;
use Youwe\CodingStandard\PhpStorm\XmlAccessorInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_TestCase;
use Youwe\CodingStandard\PhpStorm\Patcher\FileTemplatesPatcher;
use SimpleXMLElement;

/**
 * @coversDefaultClass \Youwe\CodingStandard\PhpStorm\Patcher\FileTemplatesPatcher
 */
class FileTemplatesPatcherTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::patch
     * @covers ::patchWorkspaceConfig
     */
    public function testPatch()
    {
        $accessor = $this->createMock(XmlAccessorInterface::class);

        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $ideConfigFs = $this->createMock(FilesystemInterface::class);
        $ideConfigFs
            ->expects($this->once())
            ->method('has')
            ->with('workspace.xml')
            ->willReturn(true);

        $ideConfigFs
            ->expects($this->once())
            ->method('read')
            ->with('workspace.xml')
            ->willReturn('<config/>');

        $child = new SimpleXMLElement('<some_data/>');
        $accessor->expects($this->once())
            ->method('getDescendant')
            ->with(
                $this->isInstanceOf(SimpleXMLElement::class),
                $this->isType('array')
            )
            ->willReturn($child);

        $accessor
            ->expects($this->once())
            ->method('setAttributes')
            ->with(
                $child,
                $this->isType('array')
            );

        $ideConfigFs
            ->expects($this->once())
            ->method('put')
            ->with('workspace.xml', $this->isType('string'));

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $ideConfigFs,
                'getDefaultsFilesystem' => $defaultsFs
            ]
        );

        (new FileTemplatesPatcher($accessor))->patch($environment);
    }

    /**
     * @return void
     *
     * @covers ::patchWorkspaceConfig
     */
    public function testPatchNoWorkspace()
    {
        $accessor   = $this->createMock(XmlAccessorInterface::class);
        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $ideConfigFs = $this->createMock(FilesystemInterface::class);
        $ideConfigFs
            ->expects($this->once())
            ->method('has')
            ->with('workspace.xml')
            ->willReturn(false);

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $ideConfigFs,
                'getDefaultsFilesystem' => $defaultsFs
            ]
        );

        (new FileTemplatesPatcher($accessor))->patch($environment);
    }
}
