<?php

use TheBachtiarz\AdditionalAttribute\Interfaces\AdditionalAttributeInterface;

/**
 * thebachtiarz additional attribute config
 *
 * @param string|null $keyName config key name | null will return all
 * @return mixed
 */
function tbadtattrconfig(?string $keyName = null)
{
    $configName = AdditionalAttributeInterface::ADDITIONAL_ATTRIBUTE_CONFIG_NAME;

    return iconv_strlen($keyName)
        ? config("{$configName}.{$keyName}")
        : config("{$configName}");
}
