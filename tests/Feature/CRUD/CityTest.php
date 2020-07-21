<?php

namespace Tests\Feature\CRUD;

use App\Models\Auth\User;
use App\Models\CRUD\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityTest extends TestCase
{
    public function testGetIndexPage()
    {
        $user = factory(User::class)->make();

        $this->actingAs($user)
            ->get('/city')
            ->assertStatus(200);

        $response = $this->actingAs($user)
                        ->get('/city')
                        ->assertViewHas('cities');

        $citiesOnView = $response->original['cities'];

        $this->assertEquals(50, count($citiesOnView));
    }

      
    public function testPostCity()
    {
        $city = factory(City::class)->make();
        $city->save();
        $this->assertDatabaseHas('cities', [
            'name' => $city->name,
            'population' => $city->population
        ]);
    }

    
    public function testPostCityMethod()
    {
        $user = factory(User::class)->make();
        $city = factory(City::class)->make();
         
        $this->actingAs($user)->get('/city/create'); 

        $this->post('/city', [
            '_token' => csrf_token(),
            'name' => $city->name,
            'postal_code' => $city->postal_code,
            'population' => $city->population,
            'region' => $city->region,
            'country' => $city->country,
        ])
            ->assertStatus(302)
            ->assertSessionHas('success');

        $this->assertEquals(session('success'), 'City created successfully !');
  
    }

    public function testUpdateCity()
    {
        $user = factory(User::class)->make();
        $city = factory(City::class)->make();
        $city2 = factory(City::class)->make();
        
        $city->save();
        $this->assertDatabaseHas('cities', $city->toArray());

        $this->actingAs($user)->get('/city/{$city->id}/edit'); 
        $response = $this->put('/city/{$city->id}', [
            '_token' => csrf_token(),
            'name' => $city2->name,
            'postal_code' => $city2->postal_code,
            'population' => $city2->population,
            'region' => $city2->region,
            'country' => $city2->country,
        ])
         ->assertStatus(302)
         ->assertSessionHas('success');
         
        $this->assertEquals(session('success'), 'City updated successfully !');

    }

    public function testDeleteCity()
    {
        $user = factory(User::class)->make();
        $city = factory(City::class)->make();
        
        $city->save();
        $this->assertDatabaseHas('cities', $city->toArray());

        $this->actingAs($user)->get('/city');

        $this->delete('/city/{$city->id}', ['_token' => csrf_token()])
            ->assertStatus(302)
            ->assertSessionHas('success');
         
        $this->assertEquals(session('success'), 'City deleted successfully !');

    }
}
