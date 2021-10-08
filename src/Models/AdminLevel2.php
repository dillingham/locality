<?php

namespace Dillingham\Locality\Models;

use Illuminate\Database\Eloquent\Model;
use Dillingham\Locality\Relations;

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
