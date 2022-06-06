<?php

namespace TheBachtiarz\AdditionalAttribute\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use TheBachtiarz\AdditionalAttribute\Traits\Model\{AdditionalAttributeMapTrait, AdditionalAttributeScopeTrait};

class AdditionalAttribute extends Model
{
    use AdditionalAttributeMapTrait, AdditionalAttributeScopeTrait;

    /**
     * The attributes that that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'value'
    ];

    // ? Relations
    /**
     * Modelable morph | belongs to.
     *
     * @return MorphTo
     */
    public function modelable(): MorphTo
    {
        return $this->morphTo();
    }
}
