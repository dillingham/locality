<?php
//
//namespace Tests\Feature;
//
//use App\Models\Location\City;
//use App\Models\Location\Country;
//use App\Models\Location\Neighborhood;
//use App\Models\Location\State;
//use Dillingham\Locality\Tests\TestCase;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
//
//class DependentFilterTest extends TestCase
//{
//    use RefreshDatabase;
//
//    public function test_countries()
//    {
//        Country::create(['title' => 'USA']);
//
//        $this->get('/api/locations/countries')
//            ->assertStatus(200)
//            ->assertJsonCount(1)
//            ->assertJsonPath('0.title', 'USA');
//
//    }
//
//    public function test_states()
//    {
//        $country = Country::create(['title' => 'USA']);
//
//        State::create(['title' => 'NY', 'country_id' => $country->id]);
//        State::create(['title' => 'Belgium', 'country_id' => $country->id + 1]);
//
//        $this->get('/api/locations/states?country_id=' . $country->id)
//            ->assertStatus(200)
//            ->assertJsonCount(1)
//            ->assertJsonPath('0.title', 'NY');
//    }
//
//    public function test_cities()
//    {
//        $country = Country::create(['title' => 'USA']);
//
//        $state = State::create(['title' => 'NY', 'country_id' => $country->id]);
//
//        City::create(['title' => 'Brooklyn', 'state_id' => $state->id, 'country_id' => $country->id]);
//        City::create(['title' => 'Mexico City', 'state_id' => $state->id + 1, 'country_id' => $country->id]);
//
//        $this->get('/api/locations/cities?state_id=' . $state->id)
//            ->assertStatus(200)
//            ->assertJsonCount(1)
//            ->assertJsonPath('0.title', 'Brooklyn');
//    }
//
//    public function test_neighborhoods()
//    {
//        $country = Country::create(['title' => 'USA']);
//        $state = State::create(['title' => 'NY', 'country_id' => $country->id]);
//        $city = City::create(['title' => 'Brooklyn', 'state_id' => $state->id, 'country_id' => $country->id]);
//        Neighborhood::create(['title' => 'Greenpoint', 'city_id' => $city->id, 'state_id' => $state->id, 'country_id' => $country->id]);
//        Neighborhood::create(['title' => 'Not greenpoint', 'city_id' => $city->id + 1, 'state_id' => $state->id, 'country_id' => $country->id]);
//
//        $this->get('/api/locations/neighborhoods?city_id=' . $city->id)
//            ->assertStatus(200)
//            ->assertJsonCount(1)
//            ->assertJsonPath('0.title', 'Greenpoint');
//    }
//}
