<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DBTestCase;

class AddAvatarTest extends DBTestCase
{
    /** @test */
    public function only_members_can_add_avatars()
    {




        // send post request to add  avatars
//        $this->expectException(AuthenticationException::class);
//        $this->post('/api/users/1/avatar');

        //These are both the same

        $this->withExceptionHandling();
        $this->postJson('/api/users/1/avatar')->assertStatus(401);


        // 401 is the unauthorized response
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn()->withExceptionHandling();

        $this->postJson('/api/users/' .auth()->id(). '/avatar', ['avatar' => 'not-an-image'])
            ->assertStatus(422);
    }

    /** @test */
    public function a_valid_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');
        $this->postJson('/api/users/' .auth()->id(). '/avatar', [
            'avatar' =>  $file = UploadedFile::fake()->image('avatar.jpg')
            ]);

        $this->assertEquals(auth()->user()->avatar_path, 'avatars/' . $file->hashName());

        Storage::disk('public')->assertExists('avatars/' .$file->hashName());

    }
}