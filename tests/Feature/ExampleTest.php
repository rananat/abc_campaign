<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_is_eligible()
    {
        //Customer id upto 900 are eligible and > 900 are not eligible
        $response = $this->postJson('/api/isEligibleForCampaign', ['id' => 2]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'msg' => 'Eligible and Voucher locked'
            ]);
    }

    public function test_validate_submission()
    {
        $response = $this->postJson('/api/validateSubmission', ['id' => 2]);
 
        $response
            ->assertStatus(200);
    }
}
