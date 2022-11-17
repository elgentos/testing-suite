# Introduction

This is the Youwe coding standard, it contains rule sets for PHPCS and PHPMD.

# Installation

Use composer to install the coding standard in the home directory.

```shell
composer global require youwe/coding-standard
```

# Configuring PHPStorm to use the coding standard.

First configure PHPStorm to use the right phpcs command.

Go to __Settings > Languages & Frameworks > PHP > Code Sniffer__. Choose
"Local" for the path and fill in the full path to
`~/.config/composer/vendor/bin/phpcs`

Then go to __Settings > Editor > Inspections__ and search for PHP Code Sniffer
Validation. Select Custom and the add the path to
`~/.config/composer/vendor/youwe/coding-standard/src/Youwe`

# Using the coding standard in a project

To use the standard in a project the standard needs to be required in composer.

```shell
cd <project_directory>
composer require youwe/coding-standard --dev
```

This will add the coding standard to the vendor directory of the project.

To let phpcs use the coding standard add a file phpcs.xml to the root of the
project.

```xml
<?xml version="1.0"?>
<ruleset>
    <rule ref="./vendor/youwe/coding-standard/src/Youwe"/>
</ruleset>
```

The standard can be checked from the command line by going to the directory.

```shell
cd <project_directory>
./vendor/bin/phpcs ./src
```

# Configuring PHP CodeSniffer to also show less severe messages

By default PHP CodeSniffer shows only messages with a severity higher than
__5__. The Youwe coding standard also has some messages with a lower
severity. These are messages that encourage a better way of coding but should
not block a pull request.

To configure phpcs to show also these messages execute the following command.

```shell
~/.config/composer/vendor/bin/phpcs --config-set severity 1
```
