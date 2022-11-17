<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Youwe\CodingStandard\PhpStorm\Filesystem;
use RuntimeException;

/**
 * @coversDefaultClass \Youwe\CodingStandard\PhpStorm\Filesystem
 * @SuppressWarnings(PHPMD)
 */
class FilesystemTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $vfs;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->vfs = vfsStream::setup(sha1(__FILE__));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(
            Filesystem::class,
            new Filesystem($this->vfs->url())
        );
    }

    /**
     * @param string $path
     * @param string $contents
     *
     * @return void
     *
     * @dataProvider readWriteDataProvider
     *
     * @covers ::read
     */
    public function testRead(
        string $path,
        string $contents
    ) {
        $filesystem = new Filesystem($this->vfs->url());

        $this->createFile($path, $contents);
        $this->assertEquals($contents, $filesystem->read($path));
    }

    /**
     * @return void
     *
     * @covers ::read
     */
    public function testReadExceptionReadable()
    {
        $this->expectException(RuntimeException::class);
        $filesystem = new Filesystem($this->vfs->url());

        $this->createFile('foo.txt', 'foo', 0000);
        $filesystem->read('foo.txt');
    }

    /**
     * @return void
     *
     * @covers ::read
     */
    public function testReadExceptionDir()
    {
        $this->expectException(RuntimeException::class);
        $filesystem = new Filesystem($this->vfs->url());

        $filesystem->read('');
    }

    /**
     * @param string $path
     * @param string $contents
     *
     * @return void
     *
     * @dataProvider readWriteDataProvider
     *
     * @covers ::has
     */
    public function testHas(
        string $path,
        string $contents
    ) {
        $filesystem = new Filesystem($this->vfs->url());

        $this->assertFalse($filesystem->has($path));
        $this->createFile($path, $contents);
        $this->assertTrue($filesystem->has($path));
    }

    /**
     * @param string $path
     * @param string $contents
     *
     * @return void
     *
     * @dataProvider readWriteDataProvider
     *
     * @covers ::put
     */
    public function testPut(
        string $path,
        string $contents
    ) {
        $filesystem = new Filesystem($this->vfs->url());

        $filesystem->put($path, $contents);
        $this->assertEquals($contents, $this->readFile($path));
    }

    /**
     * @return void
     *
     * @covers ::put
     */
    public function testPutExceptionWritableFile()
    {
        $this->expectException(RuntimeException::class);
        $filesystem = new Filesystem($this->vfs->url());

        $this->createFile('path/to/foo.txt', 'foo', 0000);
        $filesystem->put('path/to/foo.txt', 'new_foo');
    }

    /**
     * @return void
     *
     * @covers ::put
     */
    public function testPutExceptionWritableDirectory()
    {
        $this->expectException(RuntimeException::class);
        $filesystem = new Filesystem($this->vfs->url());

        $this->createDir('path/to', 0000);
        $filesystem->put('path/to/foo.txt', 'new_foo');
    }

    /**
     * @return array
     */
    public function readWriteDataProvider(): array
    {
        return [
            [
                'path/to/file.txt',
                'some_contents'
            ]
        ];
    }

    /**
     * @return void
     *
     * @covers ::createDir
     */
    public function testCreateDir()
    {
        $filesystem = new Filesystem($this->vfs->url());

        $this->assertFalse($this->dirExists('foo/bar'));
        $filesystem->createDir('foo/bar');
        $this->assertTrue($this->dirExists('foo/bar'));
    }

    /**
     * @return void
     *
     * @covers ::createDir
     */
    public function testCreateDirExceptionFile()
    {
        $this->expectException(RuntimeException::class);
        $filesystem = new Filesystem($this->vfs->url());

        $this->createFile('foo/bar', 'foo');
        $filesystem->createDir('foo/bar');
    }

    /**
     * @return void
     *
     * @covers ::createDir
     */
    public function testCreateDirExceptionWritable()
    {
        $this->expectException(RuntimeException::class);
        $filesystem = new Filesystem($this->vfs->url());

        $this->createDir('foo', 0000);
        $filesystem->createDir('foo/bar');
    }

    /**
     * @param array  $files
     * @param string $path
     * @param array  $expected
     *
     * @return void
     *
     * @dataProvider listFilesDataProvider
     *
     * @covers ::listFiles
     */
    public function testListFiles(
        array $files,
        string $path,
        array $expected
    ) {
        $filesystem = new Filesystem($this->vfs->url());

        foreach ($files as $file) {
            $this->createFile($file, '');
        }

        $this->assertEquals($expected, $filesystem->listFiles($path));
    }

    /**
     * @return void
     *
     * @covers ::listFiles
     */
    public function testListFilesException()
    {
        $this->expectException(RuntimeException::class);
        $filesystem = new Filesystem($this->vfs->url());

        $filesystem->listFiles('foo/bar');
    }

    /**
     * @return array
     */
    public function listFilesDataProvider(): array
    {
        return [
            [
                [
                    'foo.txt',
                    'foo/bar.txt',
                    'foo/bar/baz.txt',
                    'foo/bar/qux.txt',
                    'bar/baz.txt'
                ],
                'foo',
                [
                    'foo/bar.txt',
                    'foo/bar/baz.txt',
                    'foo/bar/qux.txt'
                ]
            ]
        ];
    }

    /**
     * @return void
     *
     * @covers ::getPath
     */
    public function testGetPath()
    {
        $filesystem = new Filesystem($this->vfs->url());

        $this->createFile('path/to/foo.txt', 'foo');
        $this->assertTrue($filesystem->has('///path//to/foo.txt'));
    }

    /**
     * @param string $path
     * @param string $contents
     * @param int    $chmod
     *
     * @return void
     */
    private function createFile(
        string $path,
        string $contents,
        int $chmod = 0777
    ) {
        $this->createDir(dirname($path));
        $url = $this->vfs->url() . '/' . $path;
        file_put_contents($url, $contents);
        chmod($url, $chmod);
    }

    /**
     * @param string $path
     * @param int    $chmod
     *
     * @return void
     */
    private function createDir(
        string $path,
        int $chmod = 0777
    ) {
        $url = $this->vfs->url() . '/' . $path;
        if (!file_exists($url)) {
            mkdir($url, 0777, true);
        }

        chmod($url, $chmod);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function readFile(string $path): string
    {
        $url = $this->vfs->url() . '/' . $path;
        return file_get_contents($url);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function dirExists(string $path): bool
    {
        $url = $this->vfs->url() . '/' . $path;
        return file_exists($url) && is_dir($url);
    }
}
