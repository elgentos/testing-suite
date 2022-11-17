<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Tests\Patcher;

use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\FilesystemInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_TestCase;
use Youwe\CodingStandard\PhpStorm\Patcher\CodeStylePatcher;

/**
 * @coversDefaultClass \Youwe\CodingStandard\PhpStorm\Patcher\CodeStylePatcher
 */
class CodeStylePatcherTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::patch
     */
    public function testPatch()
    {
        $ideConfigFs = $this->createMock(FilesystemInterface::class);
        $ideConfigFs
            ->expects($this->once())
            ->method('put')
            ->with('codeStyleSettings.xml', '<xml/>');

        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('read')
            ->with('codeStyleSettings.xml')
            ->willReturn('<xml/>');

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $ideConfigFs,
                'getDefaultsFilesystem' => $defaultsFs
            ]
        );

        (new CodeStylePatcher())->patch($environment);
    }
}
