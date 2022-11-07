# elgentos/testing-suite

This is mainly a placeholder for future changes to the testing suite.

This uses `youwe/testing-suite`, but we can add/change tests as we like. 

## Installation

```
composer require elgentos/testing-suite --dev
```

## Run it

```
vendor/bin/grumphp run
```

# Gitlab CI/CD

If you are implementing this in an existing project, do the work to make all tests pass. As soon as all tests pass, make sure to make the static testing job required in our internal Gitlab CI/CD, by adding this to the projects' `.gitlab-ci.yml`:

```
static:testingsuite:
  allow_failure: false
```
