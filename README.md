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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\AdditionalAttribute\Service\AdditionalAttributes;

class User extends Model
{
    use HasFactory;

    use AdditionalAttributes;
}
```
-------
### Feature

- Add or Update Attribute. <br/>
Create new attribute or Update existing attribute in model.

``` bash
public function setAttr(string $attrName, $attrValue): ?AdditionalAttribute;
```
``` bash
App\Models\User::find(1)->setAttr('attrName', 'attrValue');
```
- Get Attribute By Name. <br/>
Get attribute in model by attribute name.
``` bash
public function getAttr(string $attrName): ?mixed;
```
``` bash
App\Models\User::find(1)->getAttr('attrName');
```
- Get All Attributes. <br/>
Get all attribute(s) in model.
``` bash
public function getAttrs(): ?mixed;
```
``` bash
App\Models\User::find(1)->getAttrs();
```
- Get All Attributes as Array. <br/>
Get all attribute(s) in model as array.
``` bash
public function getAttrsAsArray(): array;
```
``` bash
App\Models\User::find(1)->getAttrsAsArray();
```
-------
