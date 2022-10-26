<?php

namespace TheBachtiarz\AdditionalAttribute\Services;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use TheBachtiarz\AdditionalAttribute\Interfaces\Model\AdditionalAttributeModelInterface;
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
     * Value type who need to parsing
     *
     * @var array
     */
    protected static array $valueTypeNeedToParse = [];

    // ? Public Methods
    /**
     * Get attribute by name
     *
     * @param string $attrName
     * @param boolean $map
     * @return AdditionalAttribute|array|null
     */
    public function getAttr(string $attrName, bool $map = false): AdditionalAttribute|array|null
    {
        try {
            $_attribute = $this->attributes()->getByName($attrName)->first();

            throw_if(!$_attribute, 'Exception', "Attribute not Found");

            $_attribute = self::decodeValueResolver($_attribute);

            return $map ? $_attribute->simpleListMap() : $_attribute;
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Get attribute by name.
     * Get only value.
     *
     * @param string $attrName
     * @param boolean $withKey default: false
     * @return mixed
     */
    public function getAttrValue(string $attrName, bool $withKey = false): mixed
    {
        try {
            $_attribute = $this->getAttr($attrName);

            return $withKey
                ? [$_attribute[AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_NAME] => $_attribute[AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_VALUE]]
                : $_attribute[AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_VALUE];
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Get all attribute belongs to model
     *
     * @param boolean $map
     * @return AdditionalAttribute|array|null
     */
    public function getAttrs(bool $map = false): AdditionalAttribute|array|null
    {
        try {
            $_attributes = $this->attributes();

            throw_if(!$_attributes->count(), 'Exception', "There is no Attributes");

            $_result = [];

            foreach ($_attributes->get() as $key => &$_attribute) {
                /**
                 * @var AdditionalAttribute $_attribute
                 */
                $_attribute = self::decodeValueResolver($_attribute);

                $_result[] = $map ? $_attribute->simpleListMap() : $_attribute;
            }

            return $_result;
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Get model attribute(s).
     * Get only values.
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
                $result[$_attribute[AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_NAME]] = $_attribute[AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_VALUE];

            $result = [tbadtattrconfig('return_key_name') => $result];
        } catch (\Throwable $th) {
        } finally {
            return $result;
        }
    }

    /**
     * Create or update attribute
     *
     * @param string $attrName
     * @param mixed $attrValue
     * @return AdditionalAttribute|null
     */
    public function setAttr(string $attrName, mixed $attrValue): ?AdditionalAttribute
    {
        try {
            $_attribute = $this->getAttr($attrName);

            [$valueType, $valueResult] = self::encodeValueResolver($attrValue);

            /**
             * If exist before, then update
             */
            if ($_attribute) {
                $_attribute->update([
                    AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_TYPE => $valueType,
                    AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_VALUE => $valueResult
                ]);

                return $_attribute;
            }

            /**
             * If not exist before, then create new
             */
            return $this->attributes()->create([
                AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_NAME => $attrName,
                AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_TYPE => $valueType,
                AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_VALUE => $valueResult
            ]);
        } catch (\Throwable $th) {
            self::logCatch($th);

            return null;
        }
    }

    /**
     * Delete by attribute name
     *
     * @param string $attrName
     * @return boolean
     */
    public function deleteAttr(string $attrName): bool
    {
        try {
            return $this->getAttr($attrName)->delete();
        } catch (\Throwable $th) {
            self::logCatch($th);

            return false;
        }
    }

    // ? Public Static methods
    /**
     * Search value by attribute name
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
            /**
             * @var \Illuminate\Database\Eloquent\Builder $_attributes
             */
            $_attributes = AdditionalAttribute::searchByValueAttrName(__CLASS__, $attrName, $valueToSearch);

            throw_if(!$_attributes->count(), 'Exception', "There is no Attributes");

            foreach ($_attributes->get() as $key => &$_attribute) {
                /**
                 * @var AdditionalAttribute $_attribute
                 */
                $_attribute = self::decodeValueResolver($_attribute);

                $_result[] = $map ? $_attribute->simpleListMap() : $_attribute?->toArray();
            }
        } catch (\Throwable $th) {
        } finally {
            return $_result;
        }
    }

    // ? Private Methods
    /**
     * Encode value attribute
     *
     * @param mixed $value
     * @return array
     */
    private static function encodeValueResolver(mixed $value): array
    {
        $valueType = gettype($value);

        try {
            $valueResult = in_array($valueType, self::typeParseResolver())
                ? self::jsonEncode($value)
                : $value;

            $valueResult = $valueType === "boolean"
                ? ($valueResult ? "1" : "0")
                : $valueResult;
        } catch (\Throwable $th) {
            self::logCatch($th);
        } finally {
            return [$valueType, $valueResult];
        }
    }

    /**
     * Decode value attribute.
     * Based from parse type.
     *
     * @param AdditionalAttribute $additionalAttribute
     * @return AdditionalAttribute
     */
    private static function decodeValueResolver(AdditionalAttribute $additionalAttribute): AdditionalAttribute
    {
        try {
            if (in_array($additionalAttribute->getType(), self::typeParseResolver()))
                $additionalAttribute->setValue(self::jsonDecode($additionalAttribute->getValue()));

            return $additionalAttribute;
        } catch (\Throwable $th) {
            self::logCatch($th);

            return $additionalAttribute;
        }
    }

    /**
     * Get value type who need to parse into json
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
     * Get the attributes that belong to model
     *
     * @return MorphMany
     */
    private function attributes(): MorphMany
    {
        return $this->morphMany(AdditionalAttribute::class, 'modelable');
    }
}
