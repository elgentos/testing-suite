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
