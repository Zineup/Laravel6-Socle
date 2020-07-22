<?php

namespace Tests\Feature\CRUD;

use App\Models\Auth\User;
use App\Models\CRUD\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test for index method
     */
    public function testGetIndexPage()
    {
        $count_cities_in_db = City::all()->count();
        $response = $this->getResponse('/city', 'cities');        
        $citiesOnView = $response->original['cities'];

        $this->assertEquals($count_cities_in_db, count($citiesOnView));
    }
    
    /**
     * test for create method
     */
    public function testGetCreatePage()
    {
        $response = $this->callUrl('/city/create');
        $response->assertStatus(200);
    }
    
    /**
     * test for edit method
     */
    public function testGetEditPage()
    {        
        $city = $this->testPostCity();        
        $response = $this->getResponse('/city/'.$city->id.'/edit', 'city');        
        $cityOnView = $response->original['city'];
        
        $this->assertEquals($this->getCityReduced($city), $this->getCityReduced($cityOnView)); 
    }
    
    /**
     * test for show method
     */
    public function testGetShowPage()
    {                
        $city = $this->testPostCity();
        $response = $this->getResponse('city/'. $city->id, 'city');
        $cityOnView = $response->original['city'];
           
        $this->assertEquals($this->getCityReduced($city), $this->getCityReduced($cityOnView)); 
    }
   
    /**
     * test for posting a city
     */
    public function testPostCity()
    {
        $city = factory(City::class)->make();
        $city->save();

        $this->assertDatabaseHas('cities', [
            'name' => $city->name,
            'population' => $city->population
        ]);

        return $city;
    }
    
    /**
     * test for store method
     */
    public function testStoreCityMethod()
    {
        $city = factory(City::class)->make();        
        $this->callUrl('/city/create');

        $this->post('/city', $this->getArrayToPost($city) )
            ->assertStatus(302)
            ->assertSessionHas('success');

        $this->assertEquals(session('success'), 'City created successfully !');
  
    }

    /**
     * test for update method
     */
    public function testUpdateCity()
    {
        $city = $this->testPostCity();
        $cityForUpdate = factory(City::class)->make();
        $this->callUrl('/city/{$city->id}/edit');
        
        $this->put('/city/{$city->id}',  $this->getArrayToPost($cityForUpdate) )
            ->assertStatus(302)
            ->assertSessionHas('success');
         
        $this->assertEquals(session('success'), 'City updated successfully !');
    }

    /**
     * test for delete method
     */
    public function testDeleteCity()
    {
        $city = $this->testPostCity();
        $this->callUrl('/city');

        $this->delete('/city/{$city->id}', ['_token' => csrf_token()])
            ->assertStatus(302)
            ->assertSessionHas('success');
         
        $this->assertEquals(session('success'), 'City deleted successfully !');
    }

    /**
     * FUNCTIONS TO SIMPLIFY CODE
     */

    protected function getResponse($url, $object)
    {       
        $user = factory(User::class)->make();
        $response  = $this->actingAs($user)
            ->get($url)
            ->assertStatus(200)
            ->assertViewHas($object);
        return $response;
    }

    protected function getCityReduced($city)
    {        
        return $city->only('name', 'population', 'postal_code', 'region', 'country');
    }

    protected function callUrl($url)
    {        
        $user = factory(User::class)->make();        
        return $this->actingAs($user)->get($url);
    }
     
    protected function getArrayToPost($city)
    {
        return [
            '_token' => csrf_token(),
            'name' => $city->name,
            'postal_code' => $city->postal_code,
            'population' => $city->population,
            'region' => $city->region,
            'country' => $city->country,
        ];
    }
}
