# PHP tests — sd-theme-2026

One PHPUnit suite, integration only: the theme's PHP is hook registration and
template glue, which only means something inside WordPress. Pure logic, where
there is any, lives in the plugin.

```bash
composer run test        # or: npm run test:php
```

It boots the [WordPress core test suite](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
(`wp-phpunit/wp-phpunit`) with this theme active and checks:

- the theme is an active block theme and theme.json presets resolve;
- every block style `functions.php` registers is in the registry;
- every pattern category a pattern header names is registered (patterns with
  `Inserter: false` excepted);
- every pattern registers under its `sd-theme-2026/` slug, uses only core
  blocks WordPress knows, and renders without a PHP notice, warning or error;
- every template and part resolves from the theme file (not the database) and
  uses only known core blocks.

## Setup

> ⚠️ The WordPress test suite **drops and recreates every table** in the
> database it is given. Use a throwaway database, never the site's own.
> `wp-tests-config.php` refuses to run against `southerndestinations`.

On the local Homebrew stack, once:

```bash
mysql -uroot -e "CREATE DATABASE IF NOT EXISTS southerndestinations_tests CHARACTER SET utf8mb4"
```

The defaults then work as they are. Override with environment variables:

| Variable | Default |
| --- | --- |
| `WP_CORE_DIR` | Three levels up from the theme — the local core install |
| `TO_PLUGIN_DIR` | `wp-content/plugins/tour-operator` |
| `SD_ENH_PLUGIN_DIR` | `wp-content/plugins/sd-enhancements-2026` |
| `OLLIE_MENU_PLUGIN_DIR` | `wp-content/plugins/ollie-menu-designer` — registers the `menu` part area |
| `WP_TESTS_DB_NAME` | `southerndestinations_tests` |
| `WP_TESTS_DB_USER` / `WP_TESTS_DB_PASSWORD` | `root` / empty |
| `WP_TESTS_DB_HOST` | `127.0.0.1` |

Each plugin is loaded when it is found and skipped silently when it is not. The
suite passes without sd-enhancements, which CI cannot check out (the repository
is private). Without Ollie Menu Designer, the six `menu`-area parts raise a core
warning and fail — the theme genuinely depends on it.

## Static analysis

`composer run phpstan` (`npm run lint:phpstan`) — level 5 over `functions.php`
and `inc/`. Clean on its first run, so there is no baseline.

## Theme Check

`npm run check:theme` runs the wordpress.org Theme Check through WP-CLI
(`tests/bin/theme-check.php`), from inside the local install. Theme Check must
be installed on the site; it does not need to be active. Exits non-zero on any
REQUIRED finding.
