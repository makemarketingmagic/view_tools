<?php

namespace Makemarketingmagic\ViewTools\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return ['Makemarketingmagic\ViewTools\ViewToolsServiceProvider'];
    }
}
