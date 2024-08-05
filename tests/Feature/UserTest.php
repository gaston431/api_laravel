<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_users_list_can_be_retrieved(): void
    {
        /* $response = $this->get('/');

        $response->assertStatus(200); */

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
     
        $response = $this->get('/api/users');
     
        $response->assertOk();
    
    }
}
