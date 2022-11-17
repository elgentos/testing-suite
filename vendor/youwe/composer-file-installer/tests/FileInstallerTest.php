<?php

/**
 * Copyright © Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\Composer\Tests;

use Composer\IO\IOInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Youwe\Composer\FileInstaller;
use Youwe\FileMapping\FileMappingInterface;
use Youwe\FileMapping\FileMappingReaderInterface;

/**
 * @coversDefaultClass \Youwe\Composer\FileInstaller
 */
class FileInstallerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     */
    public function testConstructor()
    {
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(
            FileInstaller::class,
            new FileInstaller(
                $this->createMock(FileMappingReaderInterface::class)
            )
        );
    }

    /**
     * @return void
     * @covers ::installFile
     */
    public function testInstallFile()
    {
        /** @var FileMappingReaderInterface $reader */
        $reader    = $this->createMock(FileMappingReaderInterface::class);
        $installer = new FileInstaller($reader);

        $fs = vfsStream::setup(
            sha1(__METHOD__),
            null,
            [
                'source' => [
                    'foo.php' => 'Foo'
                ],
                'destination' => []
            ]
        );

        /** @var FileMappingInterface|PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this->createMock(FileMappingInterface::class);
        $mapping
            ->expects($this->once())
            ->method('getSource')
            ->willReturn(
                $fs->getChild('source/foo.php')->url()
            );

        $mapping
            ->expects($this->once())
            ->method('getDestination')
            ->willReturn(
                $fs->getChild('destination')->url() . '/foo.php'
            );

        $installer->installFile($mapping);

        $this->assertStringEqualsFile(
            $fs->getChild('destination/foo.php')->url(),
            'Foo'
        );
    }

    /**
     * @return void
     * @covers ::install
     */
    public function testInstall()
    {
        /** @var FileMappingReaderInterface|PHPUnit_Framework_MockObject_MockObject $reader */
        $reader    = $this->createMock(FileMappingReaderInterface::class);
        $installer = new FileInstaller($reader);

        $fs = vfsStream::setup(
            sha1(__METHOD__),
            null,
            [
                'source' => [
                    'foo.php' => 'Foo'
                ],
                'destination' => []
            ]
        );

        /** @var FileMappingInterface|PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this->createMock(FileMappingInterface::class);
        $mapping
            ->expects($this->once())
            ->method('getSource')
            ->willReturn(
                $fs->getChild('source/foo.php')->url()
            );

        $mapping
            ->expects($this->exactly(3))
            ->method('getDestination')
            ->willReturn(
                $fs->getChild('destination')->url() . '/foo.php'
            );

        $mapping
            ->expects($this->once())
            ->method('getRelativeDestination')
            ->willReturn('foo.php');

        $reader
            ->expects($this->exactly(4))
            ->method('valid')
            ->willReturnOnConsecutiveCalls(true, false, true, false);

        $reader
            ->expects($this->exactly(2))
            ->method('current')
            ->willReturn($mapping);

        /** @var IOInterface|PHPUnit_Framework_MockObject_MockObject $io */
        $io = $this->createMock(IOInterface::class);
        $io
            ->expects($this->once())
            ->method('write')
            ->with($this->isType('string'));

        $installer->install($io);

        $this->assertStringEqualsFile(
            $fs->getChild('destination/foo.php')->url(),
            'Foo'
        );

        $installer->install($io);
    }
}
