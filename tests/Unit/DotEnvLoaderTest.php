<?php

namespace Kuick\Tests\Dotenv;

use Kuick\Dotenv\DotEnvLoader;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Dotenv\DotEnvLoader
 */
class DotEnvLoaderTest extends TestCase
{
    public function testIfEnvironmentIsProperlyInherited(): void
    {
        putenv('TESTING=no-override');
        putenv('UNTOUCHED=untouched');
        //clear envs
        putenv('APP_ENV');
        putenv('OVERRIDE_LOCAL');
        putenv('OVERRIDE_DEV');
        putenv('OVERRIDE_DEV_LOCAL');
        DotEnvLoader::fromDirectory(dirname(__DIR__) . '/Mocks/MockProjectDir');
        assertEquals('no-override', getenv('TESTING'));
        assertEquals('local value', getenv('ONLY_LOCAL'));
        assertEquals('untouched', getenv('UNTOUCHED'));
        assertEquals('override.env.local', getenv('OVERRIDE_LOCAL'));
        assertEquals('override.env.dev', getenv('OVERRIDE_DEV'));
        assertEquals('override.env.dev.local', getenv('OVERRIDE_DEV_LOCAL'));
    }

    public function testIfProdEnvironmentIsProperlyInherited(): void
    {
        putenv('APP_ENV=prod');
        //clear envs
        putenv('OVERRIDE_LOCAL');
        putenv('OVERRIDE_DEV');
        putenv('OVERRIDE_DEV_LOCAL');
        putenv('TESTING=no-override');
        //putenv('ONLY_LOCAL=local value');
        putenv('UNTOUCHED=untouched');
        DotEnvLoader::fromDirectory(dirname(__DIR__) . '/Mocks/MockProjectDir');
        assertEquals('no-override', getenv('TESTING'));
        assertEquals('local value', getenv('ONLY_LOCAL'));
        assertEquals('untouched', getenv('UNTOUCHED'));
        assertEquals('override.env.local', getenv('OVERRIDE_LOCAL'));
        assertEquals('.env.dev', getenv('OVERRIDE_DEV'));
        assertEquals('.env.dev.local', getenv('OVERRIDE_DEV_LOCAL'));
    }
}
