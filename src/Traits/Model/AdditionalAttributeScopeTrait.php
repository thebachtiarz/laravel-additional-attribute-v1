<?php

namespace TheBachtiarz\AdditionalAttribute\Traits\Model;

/**
 * Additional Attribute Scope Trait
 */
trait AdditionalAttributeScopeTrait
{
    /**
     * get attribute by name
     *
     * @param string $attrName
     * @return object|null
     */
    public function scopeGetByName($query, string $attrName): ?object
    {
        return $query->where('name', $attrName);
    }
}
