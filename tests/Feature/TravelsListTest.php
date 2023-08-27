<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelsListTest extends TestCase
{
    use RefreshDatabase;

    public function testTravelsListReturnsPaginatedDataCorrectly(): void
    {
        Travel::factory(16)->create(['is_public' => true]);
        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function testTravelsListReturnsPublicCorrectly(): void
    {
        Travel::factory(12)->create(['is_public' => true]);
        Travel::factory(16)->create(['is_public' => false]);
        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);
        $response->assertJsonCount(12, 'data');
        $response->assertJsonPath('meta.last_page', 1);
    }


}
