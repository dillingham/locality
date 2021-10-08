<?php

namespace Dillingham\Locality\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AdminLevel2
{
    public function getAdminLevel2Attribute()
    {
        return data_get($this, 'adminLevel2Relation.display');
    }

    public function adminLevel2Relation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.admin_level_2'), 'admin_level_2_id');
    }
}
