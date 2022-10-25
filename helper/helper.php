<?php

use TheBachtiarz\AdditionalAttribute\Interfaces\Config\AdditionalAttributeConfigInterface;

/**
 * TheBachtiarz additional attribute config
 *
 * @param string|null $keyName config key name | null will return all
 * @return mixed
 */
function tbadtattrconfig(?string $keyName = null): mixed
{
    $configName = AdditionalAttributeConfigInterface::ADDITIONAL_ATTRIBUTE_CONFIG_NAME;

    return iconv_strlen($keyName)
        ? config("{$configName}.{$keyName}")
        : config("{$configName}");
}
