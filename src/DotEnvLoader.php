<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-dotenv)
 *
 * @link       https://github.com/milejko/kuick-dotenv
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Dotenv;

use FilesystemIterator;
use GlobIterator;

/**
 * Dot ENV loader
 */
class DotEnvLoader
{
    public const APP_ENV = 'APP_ENV';
    public const ENV_PROD = 'prod';
    public const ENV_DEV = 'dev';

    private const MAIN_ENV_FILE = '.env';
    private const ENV_FILE_PREFIX = '.env.';
    private const LOCAL_SUFFIX = '.local';

    public static function fromDirectory(string $dotenvDir): void
    {
        $fileMap = self::buildFileMap($dotenvDir);

        $dotEnvValues = self::parseFileFromMap($fileMap, self::MAIN_ENV_FILE);
        $dotEnvValues = [...$dotEnvValues, ...self::parseFileFromMap($fileMap, self::MAIN_ENV_FILE . self::LOCAL_SUFFIX)];

        $appEnv = self::resolveAppEnv($dotEnvValues);

        $dotEnvValues = [...$dotEnvValues, ...self::parseFileFromMap($fileMap, self::ENV_FILE_PREFIX . $appEnv)];
        $dotEnvValues = [...$dotEnvValues, ...self::parseFileFromMap($fileMap, self::ENV_FILE_PREFIX . $appEnv . self::LOCAL_SUFFIX)];

        self::pushToEnvironment($dotEnvValues);
    }

    /**
     * @return array<string, string>
     */
    private static function buildFileMap(string $dotenvDir): array
    {
        $iterator = new GlobIterator(
            $dotenvDir . DIRECTORY_SEPARATOR . self::MAIN_ENV_FILE . '*',
            FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::CURRENT_AS_PATHNAME
        );
        $fileMap = [];
        foreach ($iterator as $fileName => $filePath) {
            $fileMap[$fileName] = $filePath;
        }
        return $fileMap;
    }

    /**
     * @param array<string, string> $fileMap
     * @return array<string, string>
     */
    private static function parseFileFromMap(array $fileMap, string $fileName): array
    {
        if (!isset($fileMap[$fileName])) {
            return [];
        }
        return parse_ini_file($fileMap[$fileName]) ?: [];
    }

    /**
     * @param array<string, string> $dotEnvValues
     */
    private static function resolveAppEnv(array $dotEnvValues): string
    {
        $envFromSystem = getenv(self::APP_ENV);
        if (false !== $envFromSystem) {
            return $envFromSystem;
        }
        return $dotEnvValues[self::APP_ENV] ?? self::ENV_PROD;
    }

    /**
     * @param array<string, string> $values
     */
    private static function pushToEnvironment(array $values): void
    {
        foreach ($values as $key => $value) {
            //value already set
            if (false !== getenv($key)) {
                continue;
            }
            putenv($key . '=' . $value);
        }
    }
}
