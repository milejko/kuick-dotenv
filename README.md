# Kuick Dotenv
[![Latest Version](https://img.shields.io/github/release/milejko/kuick-dotenv.svg?cacheSeconds=3600)](https://github.com/milejko/kuick-dotenv/releases)
[![PHP](https://img.shields.io/badge/PHP-8.2%20|%208.3%20|%208.4-blue?logo=php&cacheSeconds=3600)](https://www.php.net)
[![Total Downloads](https://img.shields.io/packagist/dt/kuick/dotenv.svg?cacheSeconds=3600)](https://packagist.org/packages/kuick/dotenv)
[![GitHub Actions CI](https://github.com/milejko/kuick-dotenv/actions/workflows/ci.yml/badge.svg)](https://github.com/milejko/kuick-dotenv/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/milejko/kuick-dotenv/graph/badge.svg?token=M3FW3XYJ5J)](https://codecov.io/gh/milejko/kuick-dotenv)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?cacheSeconds=14400)](LICENSE)

## Minimalistic .env parser for PHP applications

### Key features
1. Optimized for the best performance
2. Easy to use

## Usage
1. Install kuick/dotenv package via composer
```
composer require kuick/dotenv
```
2. Place this line inside your index.php, Kernel, or console file
```
use Kuick\Dotenv\DotenvLoader;

DotenvLoader::fromDirectory('/a-directory/containing/dot-env/files');
```
