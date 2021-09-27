<?php

namespace Dillingham\Locality\Models;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    public $guarded = ['id'];

    public function getTable()
    {
        return config('locality.tables.postal_codes');
    }
}
