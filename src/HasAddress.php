<?php

namespace Dillingham\Locality;

use Dillingham\Locality\Facades\Locality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAddress
{
    protected function initializeHasAddress()
    {
        $this->mergeFillable([
            'formatted_address',
            'address_1',
            'address_2',
            'admin_level_3',
            'admin_level_2',
            'admin_level_1',
            'postal_code',
            'country',
        ]);
    }

    public static function bootHasAddress()
    {
        static::creating(function (Model $model) {
            Locality::normalize($model);
            Locality::formattedAddress($model);
        });

        static::updating(function (Model $model) {
            if (Locality::wasChanged($model)) {
                Locality::normalize($model);
                Locality::formattedAddress($model);
            }
        });
    }

    public function getAdminLevel1Attribute()
    {
        return data_get($this, 'adminLevel1Relation.display');
    }

    public function getAdminLevel2Attribute()
    {
        return data_get($this, 'adminLevel2Relation.display');
    }

    public function getAdminLevel3Attribute()
    {
        return data_get($this, 'adminLevel3Relation.display');
    }

    public function getPostalCodeAttribute()
    {
        return data_get($this, 'postalCodeRelation.display');
    }

    public function getCountryAttribute()
    {
        return data_get($this, 'countryRelation.display');
    }

    public function adminLevel1Relation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.admin_level_1'), 'admin_level_1_id');
    }

    public function adminLevel2Relation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.admin_level_2'), 'admin_level_2_id');
    }

    public function adminLevel3Relation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.admin_level_3'), 'admin_level_3_id');
    }

    public function postalCodeRelation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.postal_code'), 'postal_code_id');
    }

    public function countryRelation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.country'), 'country_id');
    }
}
