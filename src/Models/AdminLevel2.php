<?php

namespace Dillingham\Locality\Models;

use Dillingham\Locality\Relations;
use Illuminate\Database\Eloquent\Model;

class AdminLevel2 extends Model
{
    use Relations\AdminLevel1;
    use Relations\Country;

    public $guarded = ['id'];

    public function getTable()
    {
        return config('locality.tables.admin_level_2');
    }

    //    admin_level_1_id
    //    country_id
}
