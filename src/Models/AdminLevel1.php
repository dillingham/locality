<?php

namespace Dillingham\Locality\Models;

use Dillingham\Locality\Relations;
use Illuminate\Database\Eloquent\Model;

class AdminLevel1 extends Model
{
    use Relations\Country;

    public $guarded = ['id'];

    public function getTable()
    {
        return config('locality.tables.admin_level_1');
    }
}
