<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Seed1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (app()->environment() === 'local') {
            User::create([
                'name' => 'JohnDoe',
                'email' => 'john@doe.com',
                'password' => bcrypt('secret')
            ]);

            User::create([
                'name' => 'JaneDoe',
                'email' => 'jane@doe.com',
                'password' => bcrypt('secret')
            ]);

            create('App\User', [], 18);
            $users = User::get();
            $channels = create('App\Channel', [], 5);
            $threads = $users->each(function ($user) {

                $i = 0;
                do {
                    $i += random_int(1, 10);
                    create('App\Thread', ['user_id' => $user->id, 'channel_id' => random_int(1, 5)]);
                } while ($i < 10);
            });
            $replies = $threads->each(function ($thread) {
                $i = 0;
                do {
                    $i += random_int(1, 10);
                    create('App\Reply', ['thread_id' => $thread->id, 'user_id' => random_int(1, 20)]);
                } while ($i < 10);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
