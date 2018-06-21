<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

abstract class DBTestCase extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        DB::statement('PRAGMA foreign_keys=on');
    }

}
