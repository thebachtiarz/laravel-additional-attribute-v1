<?php

namespace TheBachtiarz\AdditionalAttribute\Service;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use TheBachtiarz\AdditionalAttribute\Models\AdditionalAttribute;
use TheBachtiarz\Toolkit\Helper\App\Converter\ArrayHelper;

/**
 * Additional Attributes Trait Service
 */
trait AdditionalAttributes
{
    use ArrayHelper;

    // ? Public Methods
    /**
     * get attribute by name
     *
     * @param string $attrName
     * @param boolean $map
     * @return mixed
     */
    public function getAttr(string $attrName, bool $map = false)
    {
        try {
            $_attribute = $this->attributes()->getByName($attrName)->first();

            throw_if(!$_attribute, 'Exception', "Attribute not Found");

            if (in_array($_attribute->type, $this->typeParseResolver()))
                $_attribute->value = self::jsonDecode($_attribute->value);

            return $map ? $_attribute->simpleListMap() : $_attribute;
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * get all attribute belongs to model
     *
     * @param boolean $map
     * @return mixed
     */
    public function getAttrs(bool $map = false)
    {
        try {
            $_attributes = $this->attributes();

            throw_if(!$_attributes->count(), 'Exception', "There is no Attributes");

            return $map ? $_attributes->get()->map->simpleListMap() : $_attributes->get();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * get model attribute(s) as array
     *
     * @return array
     */
    public function getAttrsAsArray(): array
    {
        $result = [];

        $_attributes = $this->getAttrs();

        foreach ($_attributes as $key => $_attribute) {
            if (in_array($_attribute->type, $this->typeParseResolver()))
                $_attribute->value = self::jsonDecode($_attribute->value);

            $result[$_attribute->name] = $_attribute->value;
        }

        return [tbadtattrconfig('return_key_name') => $result];
    }

    /**
     * set attribute
     *
     * @param string $attrName
     * @param mixed $attrValue
     * @return AdditionalAttribute|null
     */
    public function setAttr(string $attrName, $attrValue): ?AdditionalAttribute
    {
        try {
            $_attribute = $this->getAttr($attrName);

            [$valueType, $valueResult] = $this->valueResolver($attrValue);

            if ($_attribute) {
                $_attribute->update([
                    'type' => $valueType,
                    'value' => $valueResult
                ]);

                return $_attribute;
            }

            return $this->attributes()->create([
                'name' => $attrName,
                'type' => $valueType,
                'value' => $valueResult
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }

    // ? Private Methods
    /**
     * resolve value type
     *
     * @param mixed $value
     * @return array
     */
    private function valueResolver($value): array
    {
        $valueType = gettype($value);

        $valueResult = in_array($valueType, $this->typeParseResolver())
            ? self::jsonEncode($value)
            : $value;

        return [
            $valueType,
            $valueResult
        ];
    }

    /**
     * get value type who need to parse into json
     *
     * @return array
     */
    private function typeParseResolver(): array
    {
        return tbadtattrconfig('type_need_to_json');
    }

    // ? Relations
    /**
     * get the attributes that belong to model
     *
     * @return MorphMany
     */
    private function attributes(): MorphMany
    {
        return $this->morphMany(AdditionalAttribute::class, 'modelable');
    }
}
