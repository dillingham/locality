<?php

namespace Dillingham\Locality\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait PostalCode
{
    public function getPostalCodeAttribute()
    {
        return data_get($this, 'postalCodeRelation.display');
    }

    public function postalCodeRelation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.postal_code'), 'postal_code_id');
    }
}
