<?php

/**
 * Copyright © Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\FileMapping\Tests;

use Youwe\FileMapping\FileMappingInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Youwe\FileMapping\UnixFileMappingReader;

/**
 * @coversDefaultClass \Youwe\FileMapping\UnixFileMappingReader
 */
class UnixFileMappingReaderTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMappings
     * @covers ::next
     * @covers ::key
     * @covers ::valid
     * @covers ::rewind
     * @covers ::current
     */
    public function testIteration()
    {
        $fileSystem = vfsStream::setup(
            sha1(__METHOD__),
            null,
            [
                'files' => '{foo,bar}.php',
                'source' => [
                    'foo.php' => 'Foo'
                ],
                'destination' => []
            ]
        );

        $mappings = new UnixFileMappingReader(
            $fileSystem->getChild('source')->url(),
            $fileSystem->getChild('destination')->url(),
            $fileSystem->getChild('files')->url(),
            $fileSystem->getChild('files')->url()
        );

        foreach ($mappings as $offset => $mapping) {
            $this->assertInstanceOf(FileMappingInterface::class, $mapping);
            $this->assertIsInt($offset);
            $this->assertFileExists($mapping->getSource());
        }
    }
}
