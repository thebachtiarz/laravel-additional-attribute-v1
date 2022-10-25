<?php

namespace TheBachtiarz\AdditionalAttribute\Traits\Model;

use Illuminate\Database\Eloquent\Builder;

/**
 * Additional Attribute Scope Trait
 */
trait AdditionalAttributeScopeTrait
{
    /**
     * Get by name
     *
     * @param Builder $builder
     * @param string $attrName
     * @return Builder
     */
    public function scopeGetByName(Builder $builder, string $attrName): Builder
    {
        return $builder->where('name', $attrName);
    }

    /**
     * Search value by attribute name
     *
     * @param Builder $builder
     * @param string $classModel
     * @param string $attrName
     * @param string $valueToSearch
     * @return Builder
     */
    public function scopeSearchByValueAttrName(Builder $builder, string $classModel, string $attrName, string $valueToSearch): Builder
    {
        return $builder
            ->where('modelable_type', $classModel)
            ->getByName($attrName)
            ->where('value', 'like', "%$valueToSearch%");
    }
}
