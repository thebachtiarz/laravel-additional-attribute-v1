<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Attribute Key Name
    |--------------------------------------------------------------------------
    |
    | Here you can define the key name when result of attributes as array returned.
    |
    | ex: [ 'attributes' => [...yourAttributesValue] ]
    |
    */
    'return_key_name' => "attributes",

    /*
    |--------------------------------------------------------------------------
    | Value type who need to Parse into JSON type
    |--------------------------------------------------------------------------
    |
    | Here you can define the value type who need to parse into JSON type.
    |
    */
    'type_need_to_json' => ["array", "object"],
];
