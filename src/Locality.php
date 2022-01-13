<?php

namespace Dillingham\Locality;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Locality
{
    private array $relations = [];

    public function normalize(Model $model): Model
    {
        if (! $this->isDirty($model)) {
            return $model;
        }

        $this->syncRelations($model);

        unset(
            $model->admin_level_1,
            $model->admin_level_2,
            $model->admin_level_3,
            $model->postal_code,
            $model->country,
        );

        return $model;
    }

    public function formattedAddress(Model $model, array $values): Model
    {
        if (! $this->isDirty($model)) {
            return $model;
        }

        $model->formatted_address = ''.
            Arr::get($values, 'address_1', $model->address_1).' '.
            Arr::get($values, 'address_2', $model->address_2).', '.
            Arr::get($values, 'admin_level_2', $model->admin_level_2).', '.
            Arr::get($values, 'admin_level_1', $model->admin_level_1).' '.
            Arr::get($values, 'postal_code', $model->postal_code);

        return $model;
    }

    public function isDirty(Model $model): bool
    {
        return $model->isDirty([
            'address_1',
            'address_2',
            'admin_level_1',
            'admin_level_2',
            'admin_level_3',
            'postal_code',
            'country',
        ]);
    }

    public function syncRelations(Model $model)
    {
        $attributes = [
            'country',
            'admin_level_1',
            'admin_level_2',
            'admin_level_3',
            'postal_code',
        ];

        $values = [];

        $submitted = Arr::only(
            $model->getAttributes(),
            $attributes
        );

        if ($model->exists) {
            if (array_key_exists('country', $submitted)) {
                $submitted = array_merge($this->only($model, ['admin_level_3', 'admin_level_2', 'admin_level_1', 'postal_code']), $submitted);
            }

            if (array_key_exists('postal_code', $submitted)) {
                $submitted = array_merge($this->only($model, ['admin_level_3_id', 'admin_level_2_id', 'admin_level_1_id']), $submitted);
            }

            if (array_key_exists('admin_level_1', $submitted)) {
                $submitted = array_merge($this->only($model, ['admin_level_2']), $submitted);
            }

            if (array_key_exists('admin_level_3', $submitted)) {
                $submitted = array_merge($this->only($model, ['postal_code', 'admin_level_2_id', 'admin_level_1_id']), $submitted);
            }

            if (array_key_exists('admin_level_2', $submitted)) {
                $submitted = array_merge($this->only($model, ['admin_level_3', 'postal_code','admin_level_1_id']), $submitted);
            }

            $submitted = array_reverse($submitted);
        }

        foreach ($attributes as $attribute) {
            $default = Arr::get(config('locality.defaults', []), $attribute);
            $value = Arr::get($submitted, $attribute, $default);

            if (isset($submitted["{$attribute}_id"])) {
                $values["{$attribute}_id"] = $submitted["{$attribute}_id"];
            }

            if (! $value) {
                continue;
            }

            $modelClass = config("locality.models.$attribute");

            $relation = app($modelClass)->firstOrCreate(
                array_merge(['display' => $value], $values)
            );

            $values["{$attribute}_id"] = $relation->id;
        }

        foreach ($values as $key => $value) {
            $model->$key = $value;
        }

        $this->formattedAddress($model, $submitted);
    }

    public function country()
    {
        return app(config('locality.models.country'));
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

    public function states()
    {
        return config('locality.states');
    }

    public function only($model, array $keys): array
    {
        $output = [];

        foreach ($keys as $key) {
            $output[$key] = data_get($model, $key);
        }

        return $output;
    }
}
