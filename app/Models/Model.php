<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     *
     */
    protected $keyType = 'string';
    /**
     *
     */
    public $incrementing = false;

    /**
     *
     */
    protected $guarded = [];

    /**
     *
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     *
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
