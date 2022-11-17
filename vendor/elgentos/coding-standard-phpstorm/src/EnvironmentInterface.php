<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm;

use Composer\Composer;
use Composer\IO\IOInterface;

interface EnvironmentInterface
{
    /**
     * Get a filesystem for the IDE configuration.
     *
     * @return FilesystemInterface
     */
    public function getIdeConfigFilesystem(): FilesystemInterface;

    /**
     * Get a filesystem for the IDE Default configuration.
     *
     * @return FilesystemInterface
     */
    public function getIdeDefaultConfigFilesystem(): FilesystemInterface;

    /**
     * Get a filesystem for the default configuration.
     *
     * @return FilesystemInterface
     */
    public function getDefaultsFilesystem(): FilesystemInterface;

    /**
     * Get a filesystem for the project.
     *
     * @return FilesystemInterface
     */
    public function getProjectFilesystem(): FilesystemInterface;

    /**
     * Get the input and output helper.
     *
     * @return IOInterface
     */
    public function getInputOutput(): IOInterface;

    /**
     * Get the composer instance.
     *
     * @return Composer
     */
    public function getComposer(): Composer;
}
