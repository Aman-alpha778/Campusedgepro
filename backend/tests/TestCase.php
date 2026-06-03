<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/backend/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
