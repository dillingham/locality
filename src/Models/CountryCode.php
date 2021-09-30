<?php

namespace Dillingham\Locality\Models;

use Illuminate\Database\Eloquent\Model;

class CountryCode extends Model
{
    public $guarded = ['id'];

    public function getTable()
    {
        return config('locality.tables.country_codes');
    }
}
