Contributing
------------

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/joelbutcher/laravel-facebook-graph/pull/new).

The current stable major version is v1. The v2 is under development.

This means any new feature MUST target v2 (`master` branch).

The v1 (`1.x` branch) is maintained only for bug fixes, additional test coverage or documentation improvements.

## Code of Conduct
The code of conduct is described in [`CODE_OF_CONDUCT.md`](CODE_OF_CONDUCT.md)

## Pull Requests

- **[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - The easiest way to apply the conventions is to run [PHP Code Sniffer](#running-php-code-sniffer) as you code.
- **[PSR-4 Autoloading](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)**

- **Add tests!** - Your patch won't be accepted if it doesn't have tests.

- **Document any change in behaviour** - Make sure the README and the [documentation](https://github.com/joelbutcher/facebook-graph-sdk-php-8/tree/master/docs) are kept up-to-date.

- **Consider our release cycle** - We follow [SemVer](http://semver.org/). Randomly breaking public APIs is not an option.

- **Create topic branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please squash them before submitting. No "WIP" or "Update Foo.php" commits - PR's will be rejected!

- **Ensure tests pass!** - Please [run the tests](#running-tests) before submitting your pull request, and make sure they pass. We won't accept a patch until all tests pass.

- **Ensure no coding standards violations** - Please [run PHP Code Sniffer](#running-php-code-sniffer) using the PSR-2 standard before submitting your pull request. A violation will cause the build to fail, so please make sure there are no violations. We can't accept a patch if the build fails.

## Running Tests

``` bash
$ ./vendor/bin/phpunit
```

## Running PHP Code Sniffer

You can install [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) globally with composer.

``` bash
$ composer global require squizlabs/php_codesniffer
```

Then you can `cd` into the Facebook PHP SDK folder and run Code Sniffer against the `src/` directory.

``` bash
$ ~/.composer/vendor/bin/phpcs
```

**Happy coding**!
