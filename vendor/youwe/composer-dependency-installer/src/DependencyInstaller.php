<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\Composer\DependencyInstaller;

use Composer\Command\ConfigCommand;
use Composer\Command\RequireCommand;
use Composer\Console\Application;
use Composer\Factory;
use Composer\Json\JsonFile;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class DependencyInstaller
{
    /**
     * @var array
     */
    private $definition;

    /**
     * @var string
     */
    private $workingDir;

    /**
     * @var null|OutputInterface
     */
    private $output;

    /**
     * Constructor.
     *
     * @param string|null          $composerFile
     * @param OutputInterface|null $output
     */
    public function __construct(
        string $composerFile = null,
        OutputInterface $output = null
    ) {
        $composerFile     = $composerFile ?: Factory::getComposerFile();
        $composerJson     = new JsonFile($composerFile);
        $this->definition = $composerJson->read();
        $this->workingDir = dirname($composerFile);
        $this->output     = $output;
    }

    /**
     * Install a repository.
     *
     * @param string $name
     * @param string $type
     * @param string $url
     *
     * @return void
     */
    public function installRepository(string $name, string $type, string $url)
    {
        if (array_key_exists('repositories', $this->definition)
            && array_key_exists($name, $this->definition['repositories'])
        ) {
            return;
        }

        $application = new Application();
        $command     = new ConfigCommand();

        $definition = clone $application->getDefinition();
        $definition->addArguments($command->getDefinition()->getArguments());
        $definition->addOptions($command->getDefinition()->getOptions());

        $input = new ArrayInput(
            [
                'command' => 'config',
                'setting-key' => 'repositories.' . $name,
                'setting-value' => [
                    $type,
                    $url
                ],
                '--working-dir' => $this->workingDir
            ],
            $definition
        );

        $application->setAutoExit(false);
        $application->run($input, $this->output);
    }

    /**
     * Install a composer package.
     *
     * @param string $name
     * @param string $version
     * @param bool $dev
     * @param bool $updateDependencies
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function installPackage(string $name, string $version, bool $dev = true, bool $updateDependencies = false)
    {
        $node = $dev ? 'require-dev' : 'require';

        if (array_key_exists($node, $this->definition)
            && array_key_exists($name, $this->definition[$node])
            && $this->definition[$node][$name] === $version
        ) {
            return;
        }

        $application = new Application();
        $command     = new RequireCommand();

        $definition = clone $application->getDefinition();
        $definition->addArguments($command->getDefinition()->getArguments());
        $definition->addOptions($command->getDefinition()->getOptions());

        $input = new ArrayInput(
            [
                'command' => 'require',
                'packages' => [$name . ':' . $version],
                '--dev' => $dev,
                '-W' => $updateDependencies,
                '--no-scripts' => true,
                '--no-interaction' => true,
                '--no-plugins' => true,
                '--working-dir' => $this->workingDir
            ],
            $definition
        );

        $application->setAutoExit(false);
        $application->run($input, $this->output);
    }
}
