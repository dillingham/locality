<?php

namespace Dillingham\Locality;

use Dillingham\Locality\Facades\Locality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAddress
{
    public bool $localityRelations = false;
    public bool $localityForeignKeys = false;

    protected function initializeHasAddress()
    {
        $this->appendLocalityAttributes();
        $this->mergeLocalityFillable();
        $this->eagerloadLocality();
        $this->hideLocalityRelations();
        $this->hideLocalityForeignKeys();
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

    public function getCountryCodeAttribute()
    {
        return data_get($this, 'countryCodeRelation.display');
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

    public function countryCodeRelation(): BelongsTo
    {
        return $this->belongsTo(config('locality.models.country_code'), 'country_code_id');
    }

    private function appendLocalityAttributes(): void
    {
        $this->appends = array_merge($this->appends, [
            'admin_level_3',
            'admin_level_2',
            'admin_level_1',
            'postal_code',
            'country_code',
        ]);
    }

    private function mergeLocalityFillable(): void
    {
        $this->mergeFillable([
            'formatted_address',
            'address_1',
            'address_2',
            'admin_level_3',
            'admin_level_2',
            'admin_level_1',
            'postal_code',
            'country_code',
        ]);
    }

    private function eagerloadLocality(): void
    {
        $this->with = array_merge($this->with, [
            'adminLevel3Relation',
            'adminLevel2Relation',
            'adminLevel1Relation',
            'postalCodeRelation',
            'countryCodeRelation',
        ]);
    }

    private function hideLocalityRelations(): void
    {
        if (!$this->localityRelations) {
            $this->makeHidden([
                'adminLevel3Relation',
                'adminLevel2Relation',
                'adminLevel1Relation',
                'postalCodeRelation',
                'countryCodeRelation',
            ]);
        }
    }

    private function hideLocalityForeignKeys(): void
    {
        if (!$this->localityForeignKeys) {
            $this->makeHidden([
                'admin_level_3_id',
                'admin_level_2_id',
                'admin_level_1_id',
                'postal_code_id',
                'country_code_id',
            ]);
        }
    }
}
