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
        $directoryIterator = new GlobIterator($dotenvDir . DIRECTORY_SEPARATOR . self::MAIN_ENV_FILE . '*', FilesystemIterator::KEY_AS_FILENAME);
        $dotEnvFileList = [];
        //creating .env* files map
        foreach ($directoryIterator as $fileName => $dotEnvFile) {
            $dotEnvFileList[$fileName] = $dotEnvFile->getPathname();
        }
        //.env values
        $dotEnvValues = isset($dotEnvFileList[self::MAIN_ENV_FILE]) ? parse_ini_file($dotEnvFileList[self::MAIN_ENV_FILE]) : [];

        //.env.local values
        if (isset($dotEnvFileList[self::MAIN_ENV_FILE . self::LOCAL_SUFFIX])) {
            $dotEnvValues = array_merge(
                $dotEnvValues,
                parse_ini_file($dotEnvFileList[self::MAIN_ENV_FILE . self::LOCAL_SUFFIX])
            );
        }
        //app env calculation
        $appEnv = (false === getenv(self::APP_ENV)) ?
            ($dotEnvValues[self::APP_ENV] ?? self::ENV_PROD) :
            getenv(self::APP_ENV);

        //app env specific .env (ie. .env.prod) values
        if (isset($dotEnvFileList[self::ENV_FILE_PREFIX . $appEnv])) {
            $dotEnvValues = array_merge(
                $dotEnvValues,
                parse_ini_file($dotEnvFileList[self::ENV_FILE_PREFIX . $appEnv])
            );
        }

        //app env specific local .env (ie. .env.prod.local) values
        if (isset($dotEnvFileList[self::ENV_FILE_PREFIX . $appEnv . self::LOCAL_SUFFIX])) {
            $dotEnvValues = array_merge(
                $dotEnvValues,
                parse_ini_file($dotEnvFileList[self::ENV_FILE_PREFIX . $appEnv . self::LOCAL_SUFFIX])
            );
        }

        self::pushToEnvironment($dotEnvValues);
    }

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
