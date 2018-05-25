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

        event(new Registered(create('App\User')));
        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function a_user_can_fully_confirm_their_email_address()
    {
        $this->post('/register',[
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        $john = User::whereName('John Doe')->first();

        $this->assertFalse($john->confirmed);
        $this->assertNotNull($john->confirmation_token);

        //Let the user confirm their account

        $response = $this->get('/register/confirm?token=' . $john->confirmation_token);
        $this->assertTrue($john->fresh()->confirmed);

        $response->assertRedirect('/threads')->assertSessionHas('flash', 'Your account has been confirmed!');

    }

}