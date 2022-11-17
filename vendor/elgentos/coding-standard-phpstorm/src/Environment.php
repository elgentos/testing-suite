<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\CodingStandard\PhpStorm;

use Composer\Composer;
use Composer\IO\IOInterface;

class Environment implements EnvironmentInterface
{
    /**
     * @var FilesystemInterface
     */
    private $ideConfigFilesystem;
    /**
     * @var FilesystemInterface
     */
    private $ideDefaultFilesystem;
    /**
     * @var FilesystemInterface
     */
    private $defaultsFilesystem;
    /**
     * @var FilesystemInterface
     */
    private $projectFilesystem;
    /**
     * @var IOInterface
     */
    private $inputOutput;
    /**
     * @var Composer
     */
    private $composer;

    /**
     * Constructor.
     *
     * @param FilesystemInterface $ideConfigFilesystem
     * @param FilesystemInterface $ideDefaultFileSystem
     * @param FilesystemInterface $defaultsFilesystem
     * @param FilesystemInterface $projectFilesystem
     * @param IOInterface         $inputOutput
     * @param Composer            $composer
     */
    public function __construct(
        FilesystemInterface $ideConfigFilesystem,
        FilesystemInterface $ideDefaultFileSystem,
        FilesystemInterface $defaultsFilesystem,
        FilesystemInterface $projectFilesystem,
        IOInterface $inputOutput,
        Composer $composer
    ) {
        $this->ideConfigFilesystem  = $ideConfigFilesystem;
        $this->ideDefaultFilesystem = $ideDefaultFileSystem;
        $this->defaultsFilesystem   = $defaultsFilesystem;
        $this->projectFilesystem    = $projectFilesystem;
        $this->inputOutput          = $inputOutput;
        $this->composer             = $composer;
    }

    /**
     * Get a filesystem for the IDE configuration.
     *
     * @return FilesystemInterface
     */
    public function getIdeConfigFilesystem(): FilesystemInterface
    {
        return $this->ideConfigFilesystem;
    }

    /**
     * Get a filesystem for the IDE configuration.
     *
     * @return FilesystemInterface
     */
    public function getIdeDefaultConfigFilesystem(): FilesystemInterface
    {
        return $this->ideDefaultFilesystem;
    }

    /**
     * Get a filesystem for the default configuration.
     *
     * @return FilesystemInterface
     */
    public function getDefaultsFilesystem(): FilesystemInterface
    {
        return $this->defaultsFilesystem;
    }

    /**
     * Get a filesystem for the project.
     *
     * @return FilesystemInterface
     */
    public function getProjectFilesystem(): FilesystemInterface
    {
        return $this->projectFilesystem;
    }

    /**
     * Get the input and output helper.
     *
     * @return IOInterface
     */
    public function getInputOutput(): IOInterface
    {
        return $this->inputOutput;
    }

    /**
     * Get the composer instance.
     *
     * @return Composer
     */
    public function getComposer(): Composer
    {
        return $this->composer;
    }
}
