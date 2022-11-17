[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mediact/coding-standard-phpstorm/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mediact/coding-standard-phpstorm/?branch=master)
# coding-standard-phpstorm

Sets up PhpStorm coding standards for a project.

## Installation
This package is installed using Composer:
```
composer require mediact/coding-standard-phpstorm
```

## Configuration
Use PHPStorm's `file > settings > language > PHP` menu to point to the installed binaries for PHPMD and PHPCS.

## Live Templates
You now get live templates that can be be found in `file > settings > Editor -> Live Templates` .
These templates can be enabled/disabled.
At this moment there have been live templates created for:
* ACL
* DB Schema
* DI
* Events
* Menu
* Module
* Phtml (WIP)
* Registration
* System

Check out [this link](COMMANDS.md) to see more info

## Templates
You now get live templates that can be be found in `file > settings > Editor -> File and Code Templates` .
These can be enabled and disabled for if you want to use them or not. If no phpstorm templates are shown please restart PhpStorm.
At this moment there have been templates created for:
* ACL
* Class
* Class - Backend Controller
* Class - Block
* Class - Helper
* Class - Observer
* Class - ViewModel
* Config
* DB Schema
* DI
* Events
* Extension Attributes
* Layout
* Menu
* Module
* Registration
* Routes
* Sales
* System 
* System Include

Check out [this link](COMMANDS.md) to see all commando's that can be used.