<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\Composer\DependencyInstaller\Tests;

use Composer\Json\JsonFile;
use Seld\JsonLint\ParsingException;
use Youwe\Composer\DependencyInstaller\DependencyInstaller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @coversDefaultClass \Youwe\Composer\DependencyInstaller\DependencyInstaller
 */
class DependencyInstallerTest extends TestCase
{
    /** @var string */
    private static $directory = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        static::tearDownAfterClass();

        mkdir(static::$directory);
        chdir(static::$directory);
        file_put_contents('composer.json', '{}');
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass()
    {
        if (is_dir(static::$directory)) {
            static::rrmdir(static::$directory);
        }
    }

    /**
     * @param string $src
     *
     * @return void
     */
    private static function rrmdir(string $src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    static::rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }


    /**
     * @return DependencyInstaller
     * @covers ::__construct
     */
    public function testConstructor(): DependencyInstaller
    {
        $installer = new DependencyInstaller(
            'composer.json',
            $this->createMock(OutputInterface::class)
        );

        $this->assertInstanceOf(
            DependencyInstaller::class,
            $installer
        );

        return $installer;
    }

    /**
     * @depends      testConstructor
     * @dataProvider repositoryProvider
     *
     * @param string              $name
     * @param string              $type
     * @param string              $url
     * @param DependencyInstaller $dependencyInstaller
     *
     * @return void
     * @covers ::installRepository
     */
    public function testInstallRepository(
        string $name,
        string $type,
        string $url,
        DependencyInstaller $dependencyInstaller
    ) {
        $dependencyInstaller->installRepository($name, $type, $url);

        $jsonFile   = new JsonFile('composer.json');
        $definition = $jsonFile->read();

        $this->assertArrayHasKey('repositories', $definition);
        $this->assertArrayHasKey($name, $definition['repositories']);
        $this->assertArrayHasKey('type', $definition['repositories'][$name]);
        $this->assertArrayHasKey('url', $definition['repositories'][$name]);
        $this->assertEquals($type, $definition['repositories'][$name]['type']);
        $this->assertEquals($url, $definition['repositories'][$name]['url']);
    }

    /**
     * @return array
     */
    public function repositoryProvider(): array
    {
        return [
            ['mediact', 'composer', 'https://composer.mediact.nl']
        ];
    }

    /**
     * @depends      testConstructor
     * @dataProvider packageProvider
     *
     * @param string $name
     * @param string $version
     * @param bool $dev
     * @param bool $updateDependencies
     * @param DependencyInstaller $dependencyInstaller
     *
     * @return void
     * @throws ParsingException
     * @covers ::installPackage
     */
    public function testInstallPackage(
        string $name,
        string $version,
        bool $dev,
        bool $updateDependencies,
        DependencyInstaller $dependencyInstaller
    ) {
        $dependencyInstaller->installPackage($name, $version, $dev, $updateDependencies);

        $jsonFile   = new JsonFile('composer.json');
        $definition = $jsonFile->read();

        $node = $dev ? 'require-dev' : 'require';

        $this->assertArrayHasKey($node, $definition);
        $this->assertArrayHasKey($name, $definition[$node]);
        $this->assertEquals($version, $definition[$node][$name]);
    }

    /**
     * @return array
     */
    public function packageProvider(): array
    {
        return [
            ['psr/log', '@stable', true, false],
            ['psr/log', '@stable', false, false]
        ];
    }
}
