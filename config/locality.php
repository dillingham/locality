<?php

return [

    'default_country_code' => 'US',

    'models' => [
        'admin_level_1' => \Dillingham\Locality\Models\AdminLevel1::class,
        'admin_level_2' => \Dillingham\Locality\Models\AdminLevel2::class,
        'admin_level_3' => \Dillingham\Locality\Models\AdminLevel3::class,
        'postal_code' => \Dillingham\Locality\Models\PostalCode::class,
        'country_code' => \Dillingham\Locality\Models\CountryCode::class,
    ],

    'tables' => [
        'admin_level_1' => 'admin_level_1',
        'admin_level_2' => 'admin_level_2',
        'admin_level_3' => 'admin_level_3',
        'postal_codes'  => 'postal_codes',
        'country_codes' => 'country_codes',
    ]

];
