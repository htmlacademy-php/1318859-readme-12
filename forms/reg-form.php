<?php
return [
    'title' => 'Форма регистрации',
    'name' => 'reg-form',
    'inputs' => [
        [
            'title' => 'Электронная почта',
            'required' => true,
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Укажите эл.почту',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input) {
                    return validateFilled($input['name']);
                },
                1 => function ($input) {
                    return validateEmail($input['name']);
                },
                2 => function ($input, $configs) {
                    return validateUniqueEmail($configs['con'], $input['name']);
                }
            ],
        ],
        [
            'title' => 'Логин',
            'required' => true,
            'type' => 'text',
            'name' => 'login',
            'placeholder' => 'Укажите логин',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input) {
                    return validateFilled($input['name']);
                }
            ],
        ],
        [
            'title' => 'Пароль',
            'required' => true,
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Придумайте пароль',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input) {
                    return validateFilled($input['name']);
                }
            ],
        ],
        [
            'title' => 'Повтор пароля',
            'required' => true,
            'type' => 'password',
            'name' => 'password-repeat',
            'placeholder' => 'Повторите пароль',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input) {
                    return validatePassword($input['name'], 'password');
                }
            ],
        ],
        [
            'required' => false,
            'type' => 'file',
            'name' => 'userpic-file',
            'field_type' => 'input-file',
            'checks' => (!empty($_FILES['userpic-file']) && $_FILES['userpic-file']["error"] !== 4) ? [
                0 => function ($input) {
                    return validateImageType($input['name']);
                }
            ] : [],
        ]
    ],
];
