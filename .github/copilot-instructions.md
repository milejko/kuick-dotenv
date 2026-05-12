# Copilot Instructions

## Commands

```bash
# Run all checks (lint + static analysis + tests)
composer test:all

# Individual checks
composer test:phpunit          # PHPUnit tests with coverage
composer test:phpcs            # PSR-12 style check
composer test:phpstan          # Static analysis (level 9)
composer test:phpmd            # Mess detection

# Auto-fix code style
composer fix:phpcbf

# Run a single test file
XDEBUG_MODE=coverage vendor/bin/phpunit tests/Unit/DotEnvLoaderTest.php

# Full Docker-based run (mirrors CI)
make test
```

## Architecture

This is a zero-dependency PHP library with a single public class: `Kuick\Dotenv\DotEnvLoader` in `src/DotEnvLoader.php`.

**File loading order** (later values override earlier ones):
1. `.env`
2. `.env.local`
3. `.env.{APP_ENV}` (e.g. `.env.dev`)
4. `.env.{APP_ENV}.local` (e.g. `.env.dev.local`)

`APP_ENV` is resolved from the already-set environment first; if not set, it falls back to the value in `.env`, then defaults to `prod`.

**Key invariant**: env vars already present in the environment (via `getenv()`) are never overwritten — `pushToEnvironment()` skips existing keys.

Files are parsed with PHP's built-in `parse_ini_file()`.

## Conventions

- **PSR-12** coding standard enforced by PHPCS/PHPCBF
- **PSR-4** autoloading: `Kuick\Dotenv\` → `src/`, `Kuick\Dotenv\Tests\` → `tests/`
- Tests live in `tests/Unit/`, test fixtures/mocks in `tests/Mocks/`
- Every test class must have a `@covers \Fully\Qualified\ClassName` annotation (`forceCoversAnnotation` is enabled in `phpunit.xml`)
- PHPStan runs at **level 9** — all code must be fully type-safe
- CI runs against PHP **8.3, 8.4, 8.5** via GitHub Actions matrix
