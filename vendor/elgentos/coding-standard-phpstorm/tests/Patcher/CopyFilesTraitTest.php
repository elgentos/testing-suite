<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Tests\Patcher;

use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\FilesystemInterface;
use Youwe\CodingStandard\PhpStorm\Tests\Patcher\TestDouble\CopyFilesPatcherDouble;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Youwe\CodingStandard\PhpStorm\Patcher\CopyFilesTrait
 */
class CopyFilesTraitTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::copyDirectory
     * @covers ::copyFile
     */
    public function testPatch()
    {
        $destination = $this->createMock(FilesystemInterface::class);
        $destination
            ->expects($this->exactly(2))
            ->method('put')
            ->withConsecutive(
                ['foo/foo.xml', '<foo/>'],
                ['foo/bar.xml', '<bar/>']
            );

        $source = $this->createMock(FilesystemInterface::class);
        $source
            ->expects($this->once())
            ->method('listFiles')
            ->with('foo')
            ->willReturn(
                [
                    'foo/foo.xml',
                    'foo/bar.xml'
                ]
            );

        $source
            ->expects($this->exactly(2))
            ->method('read')
            ->willReturnMap(
                [
                    ['foo/foo.xml', '<foo/>'],
                    ['foo/bar.xml', '<bar/>']
                ]
            );

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $destination,
                'getDefaultsFilesystem' => $source
            ]
        );

        (new CopyFilesPatcherDouble())->patch($environment);
    }
}
