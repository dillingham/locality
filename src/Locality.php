<?php

namespace Dillingham\Locality;

use Illuminate\Database\Eloquent\Model;

class Locality
{
    public function normalize(Model $model): Model
    {
        $this->syncRelations($model);

        unset(
            $model->admin_level_1,
            $model->admin_level_2,
            $model->admin_level_3,
            $model->postal_code,
            $model->country_code,
        );

        return $model;
    }

    public function formattedAddress(Model $model)
    {
        $model->formatted_address = ''.
            $model->address_1.' '.
            $model->address_2.', '.
            $model->admin_level_2.', '.
            $model->admin_level_1.' '.
            $model->postal_code;
    }

    public function wasChanged(Model $model): bool
    {
        return $model->wasChanged([
            'admin_level_1',
            'admin_level_2',
            'admin_level_3',
            'postal_code',
            'country_code',
        ]);
    }

    public function syncRelations(Model $model)
    {
        $attributes = $model->getAttributes();

        if (! isset($attributes['country'])) {
            $attributes['country_code'] = config('locality.default_country_code');
        }

        $country_code = $this->countryCode()->firstOrCreate([
            'display' => $attributes['country_code'],
        ]);

        $admin_level_1 = $this->adminLevel1()->firstOrCreate([
            'display' => $attributes['admin_level_1'],
            'country_code_id' => $country_code->id,
        ]);

        $admin_level_2 = $this->adminLevel2()->firstOrCreate([
            'display' => $attributes['admin_level_2'],
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country_code->id,
        ]);

        $admin_level_3 = null;

        if (isset($attributes['admin_level_3'])) {
            $admin_level_3 = $this->adminLevel3()->firstOrCreate([
                'display' => $attributes['admin_level_3'],
                'admin_level_2_id' => $admin_level_2->id,
                'admin_level_1_id' => $admin_level_1->id,
                'country_code_id' => $country_code->id,
            ]);
        }

        $postal_code = $this->postalCode()->firstOrCreate([
            'display' => $attributes['postal_code'],
            'admin_level_3_id' => optional($admin_level_3)->id,
            'admin_level_2_id' => $admin_level_2->id,
            'admin_level_1_id' => $admin_level_1->id,
            'country_code_id' => $country_code->id,
        ]);

        $model->admin_level_3_id = optional($admin_level_3)->id;
        $model->admin_level_2_id = $admin_level_2->id;
        $model->admin_level_1_id = $admin_level_1->id;
        $model->postal_code_id = $postal_code->id;
        $model->country_code_id = $country_code->id;
    }

    public function countryCode()
    {
        return app(config('locality.models.country_code'));
    }

    public function adminLevel1()
    {
        return app(config('locality.models.admin_level_1'));
    }

    public function adminLevel2()
    {
        return app(config('locality.models.admin_level_2'));
    }

    public function adminLevel3()
    {
        return app(config('locality.models.admin_level_3'));
    }

    public function postalCode()
    {
        return app(config('locality.models.postal_code'));
    }
}
