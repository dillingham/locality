<?php

namespace Dillingham\Locality\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLevel2 extends Model
{
    public $guarded = ['id'];

    public function getTable()
    {
        return config('locality.tables.admin_level_2');
    }
}
