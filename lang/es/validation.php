<?php

return [
    'accepted' => 'El campo :attribute debe ser aceptado.',
    'active_url' => 'El campo :attribute no es una URL valida.',
    'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
    'alpha' => 'El campo :attribute solo puede contener letras.',
    'alpha_dash' => 'El campo :attribute solo puede contener letras, numeros, guiones y guiones bajos.',
    'alpha_num' => 'El campo :attribute solo puede contener letras y numeros.',
    'array' => 'El campo :attribute debe ser un arreglo.',
    'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'file' => 'El archivo :attribute debe pesar entre :min y :max kilobytes.',
        'string' => 'El campo :attribute debe tener entre :min y :max caracteres.',
        'array' => 'El campo :attribute debe tener entre :min y :max elementos.',
    ],
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmacion de :attribute no coincide.',
    'date' => 'El campo :attribute no es una fecha valida.',
    'email' => 'El campo :attribute debe ser una direccion de correo valida.',
    'exists' => 'El :attribute seleccionado es invalido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tener un valor.',
    'image' => 'El campo :attribute debe ser una imagen.',
    'in' => 'El :attribute seleccionado es invalido.',
    'integer' => 'El campo :attribute debe ser un entero.',
    'ip' => 'El campo :attribute debe ser una direccion IP valida.',
    'json' => 'El campo :attribute debe ser una cadena JSON valida.',
    'max' => [
        'numeric' => 'El campo :attribute no puede ser mayor a :max.',
        'file' => 'El archivo :attribute no puede pesar mas de :max kilobytes.',
        'string' => 'El campo :attribute no puede tener mas de :max caracteres.',
        'array' => 'El campo :attribute no puede tener mas de :max elementos.',
    ],
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'file' => 'El archivo :attribute debe pesar al menos :min kilobytes.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'array' => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'not_in' => 'El :attribute seleccionado es invalido.',
    'numeric' => 'El campo :attribute debe ser un numero.',
    'present' => 'El campo :attribute debe estar presente.',
    'regex' => 'El formato del campo :attribute es invalido.',
    'required' => 'El campo :attribute es obligatorio.',
    'same' => 'Los campos :attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file' => 'El archivo :attribute debe pesar :size kilobytes.',
        'string' => 'El campo :attribute debe tener :size caracteres.',
        'array' => 'El campo :attribute debe contener :size elementos.',
    ],
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'unique' => 'El :attribute ya ha sido utilizado.',
    'url' => 'El formato del campo :attribute es invalido.',
    'confirmed' => 'La confirmacion de contrasena no coincide.',
    'digits' => 'El campo :attribute debe tener :digits digitos.',
    'digits_between' => 'El campo :attribute debe tener entre :min y :max digitos.',
    'current_password' => 'La contrasena actual es incorrecta.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */
    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo electronico',
        'password' => 'contrasena',
        'password_confirmation' => 'confirmacion de contrasena',
    ],
];
