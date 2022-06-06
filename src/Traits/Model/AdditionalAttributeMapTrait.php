<?php

namespace TheBachtiarz\AdditionalAttribute\Traits\Model;

use TheBachtiarz\Toolkit\Helper\App\Carbon\CarbonHelper;

/**
 * Additional Attribute Map Trait
 */
trait AdditionalAttributeMapTrait
{
    use CarbonHelper;

    /**
     * Additional attribute(s) simple list map
     *
     * @return array
     */
    public function simpleListMap(): array
    {
        return [
            'attribute_name' => $this->name,
            'attribute_type' => $this->type,
            'attribute_value' => $this->value,
            'attribute_created' => self::humanDateTime($this->created_at),
            'attribute_updated' => self::humanDateTime($this->updated_at)
        ];
    }
}
