<?php

namespace Dillingham\Locality;

use Dillingham\Locality\Facades\Locality;
use Illuminate\Database\Eloquent\Model;

trait HasAddress
{
    use Relations\AdminLevel1;
    use Relations\AdminLevel2;
    use Relations\AdminLevel3;
    use Relations\PostalCode;
    use Relations\Country;

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
        static::saving(function (Model $model) {
            Locality::normalize($model);
        });
    }

    private function appendLocalityAttributes(): void
    {
        $this->appends = array_merge($this->appends, [
            'admin_level_3',
            'admin_level_2',
            'admin_level_1',
            'postal_code',
            'country',
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
            'country',
        ]);
    }

    private function eagerloadLocality(): void
    {
        $this->with = array_merge($this->with, [
            'adminLevel3Relation',
            'adminLevel2Relation',
            'adminLevel1Relation',
            'postalCodeRelation',
            'countryRelation',
        ]);
    }

    private function hideLocalityRelations(): void
    {
        if (! $this->localityRelations) {
            $this->makeHidden([
                'adminLevel3Relation',
                'adminLevel2Relation',
                'adminLevel1Relation',
                'postalCodeRelation',
                'countryRelation',
            ]);
        }
    }

    private function hideLocalityForeignKeys(): void
    {
        if (! $this->localityForeignKeys) {
            $this->makeHidden([
                'admin_level_3_id',
                'admin_level_2_id',
                'admin_level_1_id',
                'postal_code_id',
                'country_id',
            ]);
        }
    }
}
