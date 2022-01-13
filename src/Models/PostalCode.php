<?php

namespace Dillingham\Locality\Models;

use Dillingham\Locality\Relations;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use Relations\AdminLevel1;
    use Relations\AdminLevel2;
    use Relations\AdminLevel3;
    use Relations\PostalCode;
    use Relations\Country;

    public $guarded = ['id'];

    public function getTable()
    {
        return config('locality.tables.postal_codes');
    }
}
