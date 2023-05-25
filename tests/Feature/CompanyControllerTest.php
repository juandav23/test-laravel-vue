<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $token;

    public function testConnect()
    {
        $user = User::factory()->create();
        $this->token = $user->createToken('test-token')->plainTextToken;

        $this->assertNotEmpty($this->token);
    }


    public function testStoreMethodCreatesCompany()
    {
        $data = [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'zip_code' => '9989',
        ];

        $response = $this->post('/api/company', $data, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', $data);
    }

    public function testStoreMethodReturnsValidationError()
    {
        $data = [
            'name' => '',
            'address' => '',
            'zip_code' => 'abc',
        ];

        $response = $this->post('/company', $data);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'validation error',
                'errors' => [
                    'name' => 'The name field is required.',
                    'address' => 'The address field is required.',
                    'zip_code' => 'The zip code must be a number.',
                ],
            ]);
    }

    public function testStoreMethodReturnsServerError()
    {
        $this->mock(Company::class, function ($mock) {
            $mock->shouldReceive('create')->andThrow(new \Exception('Server Error'));
        });

        $data = [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'zip_code' => $this->faker->postcode,
        ];

        $response = $this->post('/company', $data);

        $response->assertStatus(500)
            ->assertJson([
                'status' => false,
                'message' => 'Server Error',
            ]);
    }
}
