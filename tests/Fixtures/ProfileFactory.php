<?php

namespace Dillingham\Locality\Tests\Fixtures;

use Dillingham\Locality\Tests\Fixtures\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'address_1' => '104 India St',
            'address_2' => '#3L',
            'admin_level_2' => 'Brooklyn',
            'admin_level_1' => 'NY',
            'postal_code' => '11222',
        ];
    }
}
