<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void{
        parent::setUp();
        \Artisan::call('passport:install');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_user()
    {
        $data = [
            'name' => 'testuser',
            'email' => 'testuser@test.com',
            'password' => 'secret',
            'c_password' => 'secret',
            'role' => 'customer',
        ];

        $this->withoutExceptionHandling();

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(201);

        $response->assertJson(['user'=> [
            'name' => 'testuser',
            'email' => 'testuser@test.com',
            'role' => 'customer',
        ]]);
    }

    /**
     * A add store feature test.
     *
     * @return void
     */
    public function test_can_get_list_of_stores()
    {
        Passport::actingAs(
            User::factory()->create(['role'=>'manager'])
        );
        $this->withoutExceptionHandling();
        $response = $this->json('GET', '/api/store');

        $response->assertStatus(200);
    }
}
