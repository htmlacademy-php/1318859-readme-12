<?php
return [
    'title' => 'Авторизация',
    'name' => 'auth-form',
    'inputs' => [
        [
            'title' => 'Email',
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Email',
            'icon' => [
                'name' => 'user',
                'width' => '19',
                'height' => '18',
            ],
        ],
        [
            'title' => 'Пароль',
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Пароль',
            'icon' => [
                'name' => 'password',
                'width' => '16',
                'height' => '20',
            ],
        ],
    ],
];
