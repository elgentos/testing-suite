<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm;

interface FilesystemInterface
{
    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * Read a path.
     *
     * @param string $path
     *
     * @return string
     */
    public function read(string $path): string;

    /**
     * Write contents to a path.
     *
     * @param string $path
     * @param string $contents
     *
     * @return bool
     */
    public function put(string $path, string $contents): bool;

    /**
     * Create a directory if it does not exist.
     *
     * @param string $path
     *
     * @return bool
     */
    public function createDir(string $path): bool;

    /**
     * List contents of a directory.
     *
     * @param string $path
     *
     * @return array
     */
    public function listFiles(string $path = ''): array;

    /**
     * Get the root that has been set.
     *
     * @return string
     */
    public function getRoot(): string;
}
