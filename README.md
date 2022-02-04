# Laravel Additional Attribute v1

### A Simple Additional Attribute for Laravel Project v1

-------

### Installation
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
php artisan vendor:publish --provider="TheBachtiarz\AdditionalAttribute\AdditionalAttributeServiceProvider"
```

- database migration
``` bash
php artisan migrate
```

### Implementation
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
### Feature

- Add or Update Attribute. <br/>
Create new attribute or Update existing attribute in model.

``` bash
/**
 * create or update attribute
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
- Get Attribute By Name. <br/>
Get attribute in model by attribute name.
``` bash
/**
 * get attribute by name
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
- Get Attribute By Name (Only value). <br/>
Get attribute in model only value by attribute name.
``` bash
/**
 * get attribute by name.
 * get only value.
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
- Get All Attributes. <br/>
Get all attribute(s) in model.
``` bash
/**
 * get all attribute belongs to model
 *
 * @param boolean $map default: false
 * @return array|null
 */
public function getAttrs(bool $map = false): ?array;
```
``` bash
App\Models\User::find(1)->getAttrs(false);
```
- Get All Attributes. <br/>
Get all attribute(s) in model only each value.
``` bash
/**
 * get model attribute(s).
 * get only values.
 *
 * @return array
 */
public function getAttrsValues(): array;
```
``` bash
App\Models\User::find(1)->getAttrsValues();
```
-------
