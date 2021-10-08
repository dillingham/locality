<?php

namespace Dillingham\Locality\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AdminLevel1
{
    public function getAdminLevel1Attribute()
    {
        return data_get($this, 'adminLevel1Relation.display');
    }

    public function adminLevel1Relation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.admin_level_1'), 'admin_level_1_id');
    }
}
