<?php
return [
    'title'  => 'Форма добавления сообщения',
    'name'   => 'message-form',
    'inputs' => [
        [
            'title'       => 'Сообщение',
            'required'    => false,
            'type'        => 'textarea',
            'name'        => 'message',
            'placeholder' => 'Ваше сообщение',
            'field_type'  => 'textarea',
            'checks'      => [
                0 => function ($input) {
                    return validate_filled($input['name']);
                },
            ],
        ],
    ],
];
