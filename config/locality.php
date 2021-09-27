<?php

return [

    'default_country' => 'US',

    'models' => [
        'admin_level_1' => \Dillingham\Locality\Models\AdminLevel1::class,
        'admin_level_2' => \Dillingham\Locality\Models\AdminLevel2::class,
        'admin_level_3' => \Dillingham\Locality\Models\AdminLevel3::class,
        'postal_code' => \Dillingham\Locality\Models\PostalCode::class,
        'country' => \Dillingham\Locality\Models\Country::class,
    ],

    'tables' => [
        'admin_level_1' => 'admin_level_1',
        'admin_level_2' => 'admin_level_2',
        'admin_level_3' => 'admin_level_3',
        'postal_codes'  => 'postal_codes',
        'countries'     => 'countries',
    ]

];
