# Version

[![License: MIT][license-mit]](LICENSE)
[![Build Status][build-status-master]][travis-ci]
[![Maintainability][maintainability-badge]][maintainability]
[![Test Coverage][coverage-badge]][coverage]

Determine your project version from either a commit.json or from your git repository.

## Usage

`composer require kba-team/version`

Simple usage in your PHP code:

```php
<?php
use kbATeam\Version\Version;
use kbATeam\Version\FileVersion;
use kbATeam\Version\GitVersion;

$version = new Version();
$version->register(new FileVersion(APP.DS.'webroot'.DS.'commit.json'));
$version->register(new GitVersion(APP));
if ($version->exists()) {
    printf('%s (rev. %s)', $version->getBranch(), $version->getCommit());
}
/**
 * prints `<branch> (rev. <commit ID>)` of your CakePHP project.
 */
```

[license-mit]: https://img.shields.io/badge/license-MIT-blue.svg
[travis-ci]: https://travis-ci.org/the-kbA-team/version
[build-status-master]: https://api.travis-ci.org/the-kbA-team/version.svg?branch=master
[maintainability-badge]: https://api.codeclimate.com/v1/badges/26201a84222a60255c2c/maintainability
[maintainability]: https://codeclimate.com/github/the-kbA-team/version/maintainability
[coverage-badge]: https://api.codeclimate.com/v1/badges/26201a84222a60255c2c/test_coverage
[coverage]: https://codeclimate.com/github/the-kbA-team/version/test_coverage
