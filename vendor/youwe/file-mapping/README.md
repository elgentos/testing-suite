# File mapping

A PHP package for mapping files from one location to another. It is used by the [youwe/composer-file-installer](https://github.com/YouweGit/composer-file-installer) package to move installed files according to the location mapping.

## Usage examples

```php
<?php 
/** 
* Create a mapping.
*/
 $mapping = new UnixFileMapping(
      __DIR__ . '/../folder/files',
      getcwd(),
      ['./dir/one','./dir/two']
      
  );

/**
* Get the relative path to the source file.
*/
$mapping->getRelativeSource();

/**
* Get the absolute path to the source file.
*/
$mapping->getSource();

/**
* Get the relative path to the destination file.
*/
$mapping->getRelativeDestination();

/**
* Get the absolute path to the destination file.
*/
$mapping->getDestination();

```

