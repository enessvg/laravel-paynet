<?php

namespace Paynet\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Paynet\PaynetServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PaynetServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Paynet' => \Paynet\Facades\Paynet::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('paynet.secret_key', 'test_secret_key');
        $app['config']->set('paynet.public_key', 'test_public_key');
        $app['config']->set('paynet.is_live', false);
    }
}
