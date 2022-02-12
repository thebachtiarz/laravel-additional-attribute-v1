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
    protected static array $valueTypeNeedToParse = [];

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

            $_attribute = self::parseValueByType($_attribute);

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
                $_attribute = self::parseValueByType($_attribute);

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

    // ? Public Static methods
    /**
     * search value by attribute name
     *
     * @param string $attrName
     * @param string $valueToSearch
     * @param boolean $map
     * @return array
     */
    public static function searchValueByAttr(string $attrName, string $valueToSearch, bool $map = false): array
    {
        $_result = [];

        try {
            $_attributes = AdditionalAttribute::searchByValueAttrName(__CLASS__, $attrName, $valueToSearch);

            throw_if(!$_attributes->count(), 'Exception', "There is no Attributes");

            foreach ($_attributes->get() as $key => $_attribute) {
                $_attribute = self::parseValueByType($_attribute);

                $_result[] = $map ? $_attribute->simpleListMap() : $_attribute;
            }
        } catch (\Throwable $th) {
            self::logCatch($th);
        } finally {
            return $_result;
        }
    }

    // ? Private Methods
    /**
     * resolve value type
     *
     * @param mixed $value
     * @return array
     */
    private static function valueResolver($value): array
    {
        $valueType = gettype($value);

        try {
            $valueResult = in_array($valueType, self::typeParseResolver())
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
    private static function parseValueByType(AdditionalAttribute $additionalAttribute): AdditionalAttribute
    {
        try {
            if (in_array($additionalAttribute->type, self::typeParseResolver()))
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
    private static function typeParseResolver(): array
    {
        $_typeNeedToParse = tbadtattrconfig('value_type_parse');

        if (count(self::$valueTypeNeedToParse))
            $_typeNeedToParse = array_merge($_typeNeedToParse, self::$valueTypeNeedToParse);

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
