<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Use RefreshDatabase by default in feature tests that extend TestCase.
     * Individual tests can opt-out or use other traits as needed.
     */
    use RefreshDatabase;
}