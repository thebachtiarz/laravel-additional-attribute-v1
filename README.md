# Laravel Additional Attribute v1

### A Simple Additional Attribute for Laravel Project v1

-------
## Requires
- [laravel/framework](https://github.com/laravel/framework/) v9.x
- [thebachtiarz/laravel-toolkit-v1](https://github.com/thebachtiarz/laravel-toolkit-v1/) v2.x

## Installation
- composer config (only if you have access)
``` bash
composer config repositories.thebachtiarz/laravel-additional-attribute-v1 git git@github.com:thebachtiarz/laravel-additional-attribute-v1.git
```

- install repository
``` bash
composer require thebachtiarz/laravel-additional-attribute-v1
```

- vendor publish
``` bash
php artisan vendor:publish --provider="TheBachtiarz\AdditionalAttribute\ServiceProvider"
```

- database migration
``` bash
php artisan migrate
```

## Implementation
- add Class Trait Service below here into Model.
``` bash
use \TheBachtiarz\AdditionalAttribute\Service\AdditionalAttributes;
```
- Example:
``` bash
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use \TheBachtiarz\AdditionalAttribute\Service\AdditionalAttributes;
}
```
-------
## Feature
- ### Add or Update Attribute. <br/>
Create new attribute or Update existing attribute in model.

``` bash
/**
 * Create or update attribute
 *
 * @param string $attrName
 * @param mixed $attrValue
 * @return AdditionalAttribute|null
 */
public function setAttr(string $attrName, $attrValue): ?AdditionalAttribute;
```
``` bash
App\Models\User::find(1)->setAttr('attrName', 'attrValue');
```
- ### Get Attribute By Name. <br/>
Get attribute in model by attribute name.
``` bash
/**
 * Get attribute by name
 *
 * @param string $attrName
 * @param boolean $map default: false
 * @return mixed
 */
public function getAttr(string $attrName, bool $map = false): mixed;
```
``` bash
App\Models\User::find(1)->getAttr('attrName', false);
```
- ### Get Attribute By Name (Only value). <br/>
Get attribute in model only value by attribute name.
``` bash
/**
 * Get attribute by name.
 * Get only value.
 *
 * @param string $attrName
 * @param boolean $withKey default: false
 * @return mixed
 */
public function getAttrValue(string $attrName, bool $withKey = false): mixed;
```
``` bash
App\Models\User::find(1)->getAttrValue('attrName', false);
```
- ### Get All Attributes. <br/>
Get all attribute(s) in model.
``` bash
/**
 * Get all attribute belongs to model
 *
 * @param boolean $map default: false
 * @return array|null
 */
public function getAttrs(bool $map = false): ?array;
```
``` bash
App\Models\User::find(1)->getAttrs(false);
```
- ### Search By Attributes. <br/>
Search value by attribute name.
``` bash
/**
 * Search value by attribute name
 *
 * @param string $attrName
 * @param string $valueToSearch
 * @param boolean $map
 * @return array
 */
public static function searchValueByAttr(string $attrName, string $valueToSearch, bool $map = false): array;
```
``` bash
App\Models\User::searchValueByAttr('attributeName', 'valueToSearch', false);
```
-------
