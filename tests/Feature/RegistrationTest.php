<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\DBTestCase;

class RegistrationTest extends DBTestCase
{
    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function a_user_can_fully_confirm_their_email_address()
    {
        Mail::fake();
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        $john = User::whereName('John Doe')->first();

        $this->assertFalse($john->confirmed);
        $this->assertNotNull($john->confirmation_token);

        //Let the user confirm their account

        $this->get(route('register.confirm',['token' => $john->confirmation_token]))
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'Your account has been confirmed!');

        $this->assertTrue($john->fresh()->confirmed);
        $this->assertNull($john->fresh()->confirmation_token);

    }

    /** @test */
    public function returns_error_when_trying_to_confirm_an_invalid_token()
    {
        $this->get(route('register.confirm',['token' => 'invalid']))
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'Unknown Token');
    }

}