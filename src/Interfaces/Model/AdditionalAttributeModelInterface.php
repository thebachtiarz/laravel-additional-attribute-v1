<?php

namespace TheBachtiarz\AdditionalAttribute\Interfaces\Model;

interface AdditionalAttributeModelInterface
{
    /**
     * Attribute fillable
     */
    public const ADDITIONAL_ATTRIBUTE_FILLABLE = [
        self::ADDITIONAL_ATTRIBUTE_NAME,
        self::ADDITIONAL_ATTRIBUTE_TYPE,
        self::ADDITIONAL_ATTRIBUTE_VALUE
    ];

    public const ADDITIONAL_ATTRIBUTE_ID = 'id';
    public const ADDITIONAL_ATTRIBUTE_MODELABLETYPE = 'modelable_type';
    public const ADDITIONAL_ATTRIBUTE_MODELABLEID = 'modelable_id';
    public const ADDITIONAL_ATTRIBUTE_NAME = 'name';
    public const ADDITIONAL_ATTRIBUTE_TYPE = 'type';
    public const ADDITIONAL_ATTRIBUTE_VALUE = 'value';

    // ? Getter Modules
    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get type
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * Get value
     *
     * @return string|null
     */
    public function getValue(): ?string;

    // ? Setter Modules
    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Set type
     *
     * @param string $type
     * @return self
     */
    public function setType(string $type): self;

    /**
     * Set value
     *
     * @param string $value
     * @return self
     */
    public function setValue(string $value): self;
}
