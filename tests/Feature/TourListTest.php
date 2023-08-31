<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourListTest extends TestCase
{
    use RefreshDatabase;

    public function testToursListShowsPaginatedDataCorrectly(): void
    {

        $travel = Travel::factory()->create();
        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get("api/v1/travels/{$travel->slug}/tours");

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function testToursListShowsPriceCorrectly(): void
    {

        $travel = Travel::factory()->create();
        Tour::factory(1)->create(['travel_id' => $travel->id, 'price' => 295]);

        $response = $this->get("api/v1/travels/{$travel->slug}/tours");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '295.00']);
    }

    public function testToursListShowsCorrectTours(): void
    {

        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get("api/v1/travels/{$travel->slug}/tours");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('meta.last_page', 1);
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function testToursListSortRequestCorrectly(): void
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory(5)->create(['travel_id' => $travel->id]);

        $sortedResponse = $this->get("api/v1/travels/{$travel->slug}/tours/?sort=price");
        $unsortedResponse = $this->get("api/v1/travels/{$travel->slug}/tours/");
        $sortedResponse->assertSuccessful();
        $sortedResponse->assertJsonCount(5, 'data');

        $this->assertNotEquals($sortedResponse, $unsortedResponse);

        $this->assertEquals($sortedResponse['data'], collect($unsortedResponse['data'])->sortBy('price')->values()->toArray());
    }

    public function testToursListValidateInvalidRequestCorrectly(): void
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get("api/v1/travels/{$travel->slug}/tours/?sort=name&filter[price_to]=ten");
        $response->assertJsonFragment(["filter.price_to" => ["The 'price_to' parameter must be numeric"]]);
        $response->assertJsonFragment(["sort" => ["The 'sort' parameter accepts only 'price' value"]]);
        $response->assertStatus(422);
    }
}
