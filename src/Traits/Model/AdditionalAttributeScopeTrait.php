<?php

namespace TheBachtiarz\AdditionalAttribute\Traits\Model;

/**
 * Additional Attribute Scope Trait
 */
trait AdditionalAttributeScopeTrait
{
    /**
     * Get attribute by name
     *
     * @param string $attrName
     * @return object|null
     */
    public function scopeGetByName($query, string $attrName): ?object
    {
        return $query->where('name', $attrName);
    }

    /**
     * Search value by attribute name
     *
     * @param string $modelClass
     * @param string $attrName
     * @param string $valueToSearch
     * @return object|null
     */
    public function scopeSearchByValueAttrName($query, string $modelClass, string $attrName, string $valueToSearch): ?object
    {
        return $query->where('modelable_type', $modelClass)
            ->getByName($attrName)
            ->where('value', 'like', "%$valueToSearch%");
    }
}
