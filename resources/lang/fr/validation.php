<?php

return [
    'required' => 'Le champ :attribute est requis.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'unique' => 'La valeur du champ :attribute est déjà utilisée.',
    'confirmed' => 'Le champ :attribute ne correspond pas.',
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'max' => [
        'string' => 'Le champ :attribute ne doit pas contenir plus de :max caractères.',
    ],
    'string' => 'Le champ :attribute doit être une chaîne de caractères.',
    'integer' => 'Le champ :attribute doit être un entier.',
    'date' => 'Le champ :attribute doit être une date.',
    'boolean' => 'Le champ :attribute doit être un booléen.',

    'image' => 'Le champ :attribute doit être une image.',
    'mimes' => 'Le champ :attribute doit être un fichier de type :values.',
    'file' => 'Le champ :attribute doit être un fichier.',
    'size' => [
        'numeric' => 'Le champ :attribute doit être :size.',
        'file' => 'Le champ :attribute doit être :size kilo-octets.',
        'string' => 'Le champ :attribute doit être :size caractères.',
        'array' => 'Le champ :attribute doit contenir :size éléments.',
    ],
];
