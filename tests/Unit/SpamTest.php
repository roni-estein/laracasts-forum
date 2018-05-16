<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    protected $spam;
    protected function setUp()
    {
        parent::setUp();
        $this->spam = new Spam();
    }

    /** @test */
    public function it_validates_spam()
    {

        $this->assertFalse($this->spam->detect('Innocent reply here'));

    }

    /** @test */
    public function a_detects_invalid_keywords()
    {
        $this->expectException(\Exception::class);
        $this->assertTrue($this->spam->detect('Yahoo Customer Support '));

    }

    /** @test */
    public function a_detects_key_held_down()
    {
        $this->expectException(\Exception::class);
        $this->assertTrue($this->spam->detect('Hello world aaaaaaaa'));


    }

}