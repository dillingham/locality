<?php

namespace Dillingham\Locality\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AdminLevel3
{
    public function getAdminLevel3Attribute()
    {
        return data_get($this, 'adminLevel3Relation.display');
    }

    public function adminLevel3Relation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.admin_level_3'), 'admin_level_3_id');
    }
}
