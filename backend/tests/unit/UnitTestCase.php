<?php

declare(strict_types=1);

namespace App;

use Override;
use PHPUnit\Framework\TestCase;

/**
 * Base class for unit tests in the application.
 *
 * This class extends PHPUnit's TestCase and provides a setup method.
 */
abstract class UnitTestCase extends TestCase
{
    /**
     * Sets up the test case.
     *
     * This method is called before each test method is executed.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
    }
}
