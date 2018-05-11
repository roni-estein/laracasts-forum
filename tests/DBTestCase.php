<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class DBTestCase extends TestCase
{
    use DatabaseMigrations;

}
