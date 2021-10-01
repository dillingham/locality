<?php

namespace Dillingham\Locality\Tests;

use Dillingham\Locality\Facades\Locality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class OptionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Route::localityDependentOptions();
    }

    public function testCountries()
    {
        Locality::countryCode()->create(['display' => 'USA']);

        $this->get('locality/country_codes')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.display', 'USA');
    }

    public function testAdminLevel1()
    {
        $country = Locality::countryCode()->create(['display' => 'USA']);

        Locality::adminLevel1()->create([
            'display' => 'NY',
            'country_code_id' => $country->id,
        ]);

        Locality::adminLevel1()->create([
            'display' => 'Belgium',
            'country_code_id' => $country->id + 1,
        ]);

        $this->get('locality/admin_level_1?country_code_id=' . $country->id)
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.display', 'NY');
    }

    public function testAdminLevel2()
    {
        $country = Locality::countryCode()->create([
            'display' => 'US',
        ]);

        $admin_level_1 = Locality::adminLevel1()->create([
            'display' => 'NY',
            'country_code_id' => $country->id,
        ]);

        Locality::adminLevel2()->create([
            'display' => 'Brooklyn',
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        Locality::adminLevel2()->create([
            'display' => 'Mexico City',
            'admin_level_1_id' => $admin_level_1->id + 1,
            'country_code_id' => $country->id,
        ]);

        $this->get('locality/admin_level_2?admin_level_1_id=' . $admin_level_1->id)
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.display', 'Brooklyn');
    }

    public function testAdminLevel3()
    {
        $country = Locality::countryCode()->create([
            'display' => 'US',
        ]);

        $admin_level_1 = Locality::adminLevel1()->create([
            'display' => 'NY',
            'country_code_id' => $country->id,
        ]);

        $admin_level_2 = Locality::adminLevel2()->create([
            'display' => 'Brooklyn',
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        Locality::adminLevel3()->create([
            'display' => 'Greenpoint',
            'admin_level_2_id' => $admin_level_2->id,
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        Locality::adminLevel3()->create([
            'display' => 'Not greenpoint',
            'admin_level_2_id' => $admin_level_2->id + 1,
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        $this->get('locality/admin_level_3?admin_level_2_id=' . $admin_level_2->id)
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.display', 'Greenpoint');
    }

    public function testPostalCodes()
    {
        $country = Locality::countryCode()->create([
            'display' => 'US',
        ]);

        $admin_level_1 = Locality::adminLevel1()->create([
            'display' => 'NY',
            'country_code_id' => $country->id,
        ]);

        $admin_level_2 = Locality::adminLevel2()->create([
            'display' => 'Brooklyn',
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        $admin_level_3 = Locality::adminLevel3()->create([
            'display' => 'Greenpoint',
            'admin_level_2_id' => $admin_level_2->id,
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        $admin_level_3_ignore = Locality::adminLevel3()->create([
            'display' => 'Not greenpoint',
            'admin_level_2_id' => $admin_level_2->id + 1,
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        Locality::postalCode()->create([
            'display' => '11222',
            'admin_level_3_id' => $admin_level_3->id,
            'admin_level_2_id' => $admin_level_2->id,
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        Locality::postalCode()->create([
            'display' => '00000',
            'admin_level_3_id' => $admin_level_3_ignore->id,
            'admin_level_2_id' => $admin_level_2->id,
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country->id,
        ]);

        $this->get('locality/postal_codes?admin_level_3_id=' . $admin_level_3->id)
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.display', '11222');
    }
}
