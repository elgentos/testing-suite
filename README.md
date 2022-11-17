# elgentos/testing-suite

This uses `youwe/testing-suite`, but we can add/change tests/packages/configuration as we like.

## Installation

```
composer require elgentos/testing-suite --dev
```

## Configuration

Add this to your `phpstan.neon`;

```
includes:
    - vendor/bitexpert/phpstan-magento/extension.neon
```

## Baselines

When implementing this into an existing project, you might want to consider generating baselines so the tests will pass without having to fix _all_ the files.

### Phpstan baseline

Generate the baseline (replace `app/design/frontend app/code` with your dirs);

```
vendor/bin/phpstan analyse --level 4 --configuration ./phpstan.neon app/code app/design/frontend --generate-baseline
```

Then add this to your `phpstan.neon`;

```
includes:
  - phpstan-baseline.neon
```

### Phpmd baseline

Generate the baseline (replace `app/design/frontend,app/code` with your dirs);

```
vendor/bin/phpmd app/design/frontend,app/code text ./phpmd.xml --generate-baseline
```

### Phpcs baseline

Generate the baseline (replace `app/design/frontend app/code` with your dirs);

```
vendor/bin/phpcs app/design/frontend app/code --extensions=php,phtml --report=\\DR\\CodeSnifferBaseline\\Reports\\Baseline --report-file=phpcs.baseline.xml --basepath=.\
```

## Run it

```
vendor/bin/grumphp run
```

# Gitlab CI/CD

If you are implementing this in an existing project, do the work to make all tests pass (or add the baselines). As soon as all tests pass, make sure to make the static testing job required in our internal Gitlab CI/CD, by adding this to the projects' `.gitlab-ci.yml`:

```
static:testingsuite:
  allow_failure: false
```

## Configure Phpstorm

This assumes you're using [our Docker environment](https://github.com/JeroenBoersma/docker-compose-development/).

1. Go to **Settings > PHP > Quality Tools**
1. Perform these steps for `PHP_CodeSniffer`, `Mess Detector`, `PHP CS Fixer` and `PHPStan`:
    1. Click on the `...` behind the Configuration dropdown.
    1. Click on the blue `+` sign.
    1. Choose `development_php81:latest`, click OK
    1. Click Ok
1. Go to **Settings > Editor > Inspections > PHP > Quality Tools**
    1. Disable `PHP CS Fixer validation`
    1. Enable `PHP Mess Detector validation`
        1. Under "Custom Rulesets", clear the list and add `vendor/youwe/coding-standard-magento2/src/YouweMagento2/`
        1. Click Apply
    1. Enable `PHP_CodeSniffer validation`
        1. Under "Coding standard", choose "YouweMagento2"
        1. Click Apply
    1. Enable `PHPStan validation`
        1. Make sure the Configuration file and the Autoload file paths are empty
        1. Make sure you have the `phpstan.neon` file in your project root
        1. Click Apply
    1. Disable `Psalm validation`
