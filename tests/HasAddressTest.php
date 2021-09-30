<?php

namespace Dillingham\Locality\Tests;

use Dillingham\Locality\Facades\Locality;
use Dillingham\Locality\Models\AdminLevel1;
use Dillingham\Locality\Models\AdminLevel2;
use Dillingham\Locality\Models\AdminLevel3;
use Dillingham\Locality\Models\CountryCode;
use Dillingham\Locality\Models\PostalCode;
use Dillingham\Locality\Tests\Fixtures\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_config()
    {
        $this->assertInstanceOf(AdminLevel1::class, Locality::adminLevel1());
        $this->assertInstanceOf(AdminLevel2::class, Locality::adminLevel2());
        $this->assertInstanceOf(AdminLevel3::class, Locality::adminLevel3());
        $this->assertInstanceOf(PostalCode::class, Locality::postalCode());
        $this->assertInstanceOf(CountryCode::class, Locality::countryCode());
    }

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
        $this->assertEquals('US', CountryCode::first()->display);
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
        $this->assertEquals(CountryCode::first()->id, $profile->country_code_id);
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
        $this->assertEquals('US', $profile->country_code);
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

    public function test_collection()
    {
        Profile::factory(10)->create();
        $profile = Profile::all()->toArray()[0];
        unset($profile['id'], $profile['created_at'], $profile['updated_at']);

        $this->assertCount(8, array_keys($profile));
        $this->assertEquals('104 India St #3L, Brooklyn, NY 11222', $profile['formatted_address']);
        $this->assertEquals('104 India St', $profile['address_1']);
        $this->assertEquals('#3L', $profile['address_2']);
        $this->assertEquals(null, $profile['admin_level_3']);
        $this->assertEquals('Brooklyn', $profile['admin_level_2']);
        $this->assertEquals('NY', $profile['admin_level_1']);
        $this->assertEquals('11222', $profile['postal_code']);
        $this->assertEquals('US', $profile['country_code']);
    }
}
