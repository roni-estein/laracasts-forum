<?php

namespace Tests\Feature;

use App\Thread;
use Tests\DBTestCase;

class SearchTest extends DBTestCase
{
    /**
     * @group external
     * @test
     */
    public function a_user_can_search_threads()
    {
        config(['scout.driver'=>'algolia']);

        $search = 'foobar';

        create(Thread::class,[],2);
        create(Thread::class,['body'=>"This body has the search term {$search} in it"],3);

        do {
            sleep(0.25);
            $results = $this->getJson("/threads/search?q={$search}")->json();
        }while(empty($results));
        $this->assertcount(3, $results);
    }

}