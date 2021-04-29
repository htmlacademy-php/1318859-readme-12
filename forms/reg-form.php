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
        ],
        [
            'title' => 'Логин',
            'required' => true,
            'type' => 'text',
            'name' => 'login',
            'placeholder' => 'Укажите логин',
            'field_type' => 'input',
        ],
        [
            'title' => 'Пароль',
            'required' => true,
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Придумайте пароль',
            'field_type' => 'input',
        ],
        [
            'title' => 'Повтор пароля',
            'required' => true,
            'type' => 'password',
            'name' => 'password-repeat',
            'placeholder' => 'Повторите пароль',
            'field_type' => 'input',
        ],
        [
            'required' => false,
            'type' => 'file',
            'name' => 'userpic-file',
            'field_type' => 'input-file',
        ]
    ],
];
