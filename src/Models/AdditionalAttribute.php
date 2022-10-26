<?php

namespace TheBachtiarz\AdditionalAttribute\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use TheBachtiarz\AdditionalAttribute\Interfaces\Model\AdditionalAttributeModelInterface;
use TheBachtiarz\AdditionalAttribute\Traits\Model\{AdditionalAttributeMapTrait, AdditionalAttributeScopeTrait};

class AdditionalAttribute extends Model implements AdditionalAttributeModelInterface
{
    use AdditionalAttributeMapTrait, AdditionalAttributeScopeTrait;

    /**
     * The attributes that that are mass assignable.
     *
     * @var array
     */
    protected $fillable = self::ADDITIONAL_ATTRIBUTE_FILLABLE;

    // ? Getter Modules
    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->__get(self::ADDITIONAL_ATTRIBUTE_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): ?string
    {
        return $this->__get(self::ADDITIONAL_ATTRIBUTE_TYPE);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): ?string
    {
        return $this->__get(self::ADDITIONAL_ATTRIBUTE_VALUE);
    }

    // ? Setter Modules
    /**
     * {@inheritDoc}
     */
    public function setName(string $name): self
    {
        $this->__set(self::ADDITIONAL_ATTRIBUTE_ID, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setType(string $type): self
    {
        $this->__set(self::ADDITIONAL_ATTRIBUTE_TYPE, $type);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setValue(string $value): self
    {
        $this->__set(self::ADDITIONAL_ATTRIBUTE_VALUE, $value);

        return $this;
    }

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
