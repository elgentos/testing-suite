<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Youwe\CodingStandard\PhpStorm\EnvironmentInterface;
use Youwe\CodingStandard\PhpStorm\FilesystemInterface;
use Youwe\CodingStandard\PhpStorm\Patcher\ConfigPatcherInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Youwe\CodingStandard\PhpStorm\InstallerPlugin;

/**
 * @coversDefaultClass \Youwe\CodingStandard\PhpStorm\InstallerPlugin
 */
class InstallerPluginTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertIsArray(
            InstallerPlugin::getSubscribedEvents()
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::onNewCodeEvent
     */
    public function testOnNewCodeEvent()
    {
        $vfs = vfsStream::setup(sha1(__FILE__));
        mkdir($vfs->url() . '/.idea');

        $config = $this->createMock(Config::class);
        $config
            ->expects($this->once())
            ->method('get')
            ->with('vendor-dir')
            ->willReturn($vfs->url() . '/vendor');

        $composer = $this->createMock(Composer::class);
        $composer
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);

        $output = $this->createMock(IOInterface::class);

        $output
            ->expects($this->once())
            ->method('write')
            ->with($this->isType('string'));

        $event = $this->createMock(Event::class);
        $event
            ->expects($this->exactly(2))
            ->method('getComposer')
            ->willReturn($composer);

        $event
            ->expects($this->exactly(2))
            ->method('getIO')
            ->willReturn($output);

        $patcher = $this->createMock(ConfigPatcherInterface::class);
        $patcher
            ->expects($this->once())
            ->method('patch')
            ->with($this->isInstanceOf(EnvironmentInterface::class));

        $plugin = new InstallerPlugin($patcher);
        $plugin->onNewCodeEvent($event);
    }
}
