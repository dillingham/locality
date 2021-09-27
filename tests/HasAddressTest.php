<?php

namespace Dillingham\Locality\Tests;

use Dillingham\Locality\Models\AdminLevel1;
use Dillingham\Locality\Models\AdminLevel2;
use Dillingham\Locality\Models\Country;
use Dillingham\Locality\Models\PostalCode;
use Dillingham\Locality\Tests\Fixtures\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_normalized_models()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $this->assertEquals('Brooklyn', AdminLevel2::first()->display);
        $this->assertEquals('NY', AdminLevel1::first()->display);
        $this->assertEquals('11222', PostalCode::first()->display);
        $this->assertEquals('US', Country::first()->display);
    }

    public function test_foreign_keys()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $this->assertEquals(AdminLevel2::first()->id, $profile->admin_level_1_id);
        $this->assertEquals(AdminLevel1::first()->id, $profile->admin_level_2_id);
        $this->assertEquals(PostalCode::first()->id, $profile->postal_code_id);
        $this->assertEquals(Country::first()->id, $profile->country_id);
    }

    public function test_relationship_accessors()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $this->assertEquals('Brooklyn', $profile->admin_level_2);
        $this->assertEquals('NY', $profile->admin_level_1);
        $this->assertEquals('11222', $profile->postal_code);
        $this->assertEquals('US', $profile->country);
    }

    public function test_formatted_address()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $this->assertEquals(
            '104 India St #3L, Brooklyn, NY 11222',
            $profile->formatted_address
        );
    }
}
