<?php

namespace Dillingham\Locality\Tests;

use Dillingham\Locality\Facades\Locality;
use Dillingham\Locality\Models\AdminLevel1;
use Dillingham\Locality\Models\AdminLevel2;
use Dillingham\Locality\Models\AdminLevel3;
use Dillingham\Locality\Models\Country;
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
        $this->assertInstanceOf(Country::class, Locality::country());
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
        $this->assertEquals('US', $profile['country']);
    }

    public function test_updating_formatted_address()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $profile->update([
            'address_2' => '#2L',
        ]);

        $this->assertEquals('104 India St #2L, Brooklyn, NY 11222', $profile->formatted_address);
    }

    public function test_updating_nested_relations_country()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
            'country' => 'US',
        ]);

        $profile->update(['country' => 'CA']);

        $this->assertEquals(2, AdminLevel2::count());
        $this->assertEquals(2, AdminLevel1::count());
        $this->assertEquals(2, PostalCode::count());
        $this->assertEquals(2, Country::count());
    }

    public function test_updating_nested_relations_postal_code()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $profile->update([
            'postal_code' => '33872',
        ]);

        $newPostalCode = PostalCode::firstWhere('display', '33872');
        $oldPostalCode = PostalCode::firstWhere('display', '!=', '33872');

        $profile = $profile->fresh();

        $this->assertEquals(0, AdminLevel3::count());
        $this->assertEquals(1, AdminLevel2::count());
        $this->assertEquals(1, AdminLevel1::count());
        $this->assertEquals(2, PostalCode::count());
        $this->assertEquals(1, Country::count());
        $this->assertEquals('33872', $profile->postalCodeRelation->display);
        $this->assertEquals('104 India St #3L, Brooklyn, NY 33872', $profile->formatted_address);
        $this->assertEquals($newPostalCode->id, $profile->postal_code_id);
        $this->assertEquals('33872', $newPostalCode->display);
        $this->assertEquals('11222', $oldPostalCode->display);
        $this->assertEquals('Brooklyn', $newPostalCode->adminLevel2Relation->display);
        $this->assertEquals('Brooklyn', $oldPostalCode->adminLevel2Relation->display);
        $this->assertEquals($newPostalCode->admin_level_2_id, $oldPostalCode->admin_level_2_id);
        $this->assertEquals('NY', $newPostalCode->adminLevel1Relation->display);
        $this->assertEquals('NY', $oldPostalCode->adminLevel1Relation->display);
        $this->assertEquals($newPostalCode->admin_level_1_id, $oldPostalCode->admin_level_1_id);
        $this->assertEquals('US', $newPostalCode->countryRelation->display);
        $this->assertEquals('US', $oldPostalCode->countryRelation->display);
        $this->assertEquals($newPostalCode->country_id, $oldPostalCode->country_id);
    }

    public function test_updating_nested_relations_admin_level_1()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $profile->update(['admin_level_1' => 'FL']);

        $this->assertEquals(0, AdminLevel3::count());
        $this->assertEquals(2, AdminLevel2::count());
        $this->assertEquals(2, AdminLevel1::count());
        $this->assertEquals(2, PostalCode::count());
        $this->assertEquals(1, Country::count());
    }

    public function test_updating_nested_relations_admin_level_2()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $profile->update(['admin_level_2' => 'Queens']);

        $this->assertEquals(0, AdminLevel3::count());
        $this->assertEquals(2, AdminLevel2::count());
        $this->assertEquals(1, AdminLevel1::count());
        $this->assertEquals(2, PostalCode::count());
        $this->assertEquals(1, Country::count());
        $this->assertEquals('104 India St #3L, Queens, NY 11222', $profile->fresh()->formatted_address);
    }

    public function test_updating_nested_relations_admin_level_3()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $profile->update(['admin_level_3' => 'Greenpoint']);

        $this->assertEquals(1, AdminLevel3::count());
        $this->assertEquals(1, AdminLevel2::count());
        $this->assertEquals(1, AdminLevel1::count());
        $this->assertEquals(2, PostalCode::count());
        $this->assertEquals(1, Country::count());
        $this->assertEquals('104 India St #3L, Brooklyn, NY 11222', $profile->fresh()->formatted_address);
    }

    public function test_updating_nested_relations_multiple()
    {
        $profile = Profile::create([
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ]);

        $profile->update([
            'admin_level_3' => 'Greenpoint',
            'admin_level_1' => 'FL',
        ]);

        $this->assertEquals(1, AdminLevel3::count());
        $this->assertEquals(2, AdminLevel2::count());
        $this->assertEquals(2, AdminLevel1::count());
        $this->assertEquals(2, PostalCode::count());
        $this->assertEquals(1, Country::count());
        $this->assertEquals('104 India St #3L, Brooklyn, FL 11222', $profile->fresh()->formatted_address);
    }
}
