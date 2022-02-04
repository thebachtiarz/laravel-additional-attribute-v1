<?php

namespace TheBachtiarz\AdditionalAttribute\Service;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use TheBachtiarz\AdditionalAttribute\Models\AdditionalAttribute;
use TheBachtiarz\Toolkit\Helper\App\Converter\ArrayHelper;
use TheBachtiarz\Toolkit\Helper\App\Log\ErrorLogTrait;

/**
 * Additional Attributes Trait Service
 */
trait AdditionalAttributes
{
    use ArrayHelper, ErrorLogTrait;

    /**
     * value type who need to parsing
     *
     * @var array
     */
    protected array $typeNeedToParse = [];

    // ? Public Methods
    /**
     * get attribute by name
     *
     * @param string $attrName
     * @param boolean $map default: false
     * @return mixed
     */
    public function getAttr(string $attrName, bool $map = false)
    {
        try {
            $_attribute = $this->attributes()->getByName($attrName)->first();

            throw_if(!$_attribute, 'Exception', "Attribute not Found");

            $_attribute = $this->parseValueByType($_attribute);

            return $map ? $_attribute->simpleListMap() : $_attribute;
        } catch (\Throwable $th) {
            self::logCatch($th);

            return null;
        }
    }

    /**
     * get attribute by name.
     * get only value.
     *
     * @param string $attrName
     * @param boolean $withKey default: false
     * @return mixed
     */
    public function getAttrValue(string $attrName, bool $withKey = false)
    {
        try {
            $_attribute = $this->getAttr($attrName);

            return $withKey
                ? [$_attribute['name'] => $_attribute['value']]
                : $_attribute['value'];
        } catch (\Throwable $th) {
            self::logCatch($th);

            return null;
        }
    }

    /**
     * get all attribute belongs to model
     *
     * @param boolean $map default: false
     * @return array|null
     */
    public function getAttrs(bool $map = false): ?array
    {
        try {
            $_attributes = $this->attributes();

            throw_if(!$_attributes->count(), 'Exception', "There is no Attributes");

            $_result = [];

            foreach ($_attributes->get() as $key => $_attribute) {
                $_attribute = $this->parseValueByType($_attribute);

                $_result[] = $map ? $_attribute->simpleListMap() : $_attribute;
            }

            return $_result;
        } catch (\Throwable $th) {
            self::logCatch($th);

            return null;
        }
    }

    /**
     * get model attribute(s).
     * get only values.
     *
     * @return array
     */
    public function getAttrsValues(): array
    {
        $result = [];

        try {
            $_attributes = $this->getAttrs();

            throw_if(!count($_attributes), 'Exception', "Attributes not found");

            foreach ($_attributes as $key => $_attribute)
                $result[$_attribute['name']] = $_attribute['value'];

            $result = [tbadtattrconfig('return_key_name') => $result];
        } catch (\Throwable $th) {
            self::logCatch($th);
        } finally {
            return $result;
        }
    }

    /**
     * create or update attribute
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
            self::logCatch($th);

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

        try {
            $valueResult = in_array($valueType, $this->typeParseResolver())
                ? self::jsonEncode($value)
                : $value;
        } catch (\Throwable $th) {
            self::logCatch($th);
        } finally {
            return [$valueType, $valueResult];
        }
    }

    /**
     * parse value attribute.
     * based from parse type.
     *
     * @param AdditionalAttribute $additionalAttribute
     * @return AdditionalAttribute
     */
    private function parseValueByType(AdditionalAttribute $additionalAttribute): AdditionalAttribute
    {
        try {
            if (in_array($additionalAttribute->type, $this->typeParseResolver()))
                $additionalAttribute->value = self::jsonDecode($additionalAttribute->value);

            return $additionalAttribute;
        } catch (\Throwable $th) {
            self::logCatch($th);

            return $additionalAttribute;
        }
    }

    /**
     * get value type who need to parse into json
     *
     * @return array
     */
    private function typeParseResolver(): array
    {
        $_typeNeedToParse = tbadtattrconfig('type_need_to_json');

        if (count($this->typeNeedToParse))
            $_typeNeedToParse = array_merge($_typeNeedToParse, $this->typeNeedToParse);

        return $_typeNeedToParse;
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
