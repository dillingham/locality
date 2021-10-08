<?php

namespace Dillingham\Locality\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Country
{
    public function getCountryAttribute()
    {
        return data_get($this, 'countryRelation.display');
    }

    public function countryRelation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.country'), 'country_id');
    }
}
