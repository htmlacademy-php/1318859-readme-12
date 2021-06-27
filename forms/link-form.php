<?php
return [
    'title' => 'Форма добавления ссылки',
    'name' => 'link-form',
    'inputs' => [
        [
            'title' => 'Заголовок',
            'required' => true,
            'type' => 'text',
            'name' => 'heading',
            'placeholder' => 'Введите заголовок',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
                }
            ],
        ],
        [
            'title' => 'Ссылка',
            'required' => true,
            'type' => 'url',
            'name' => 'url',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
                },
                1 => function ($input, $configs) {
                    return validate_url($configs['current_tab'] . '-' . $input['name']);
                }
            ],
        ],
        [
            'title' => 'Теги',
            'required' => false,
            'type' => 'text',
            'name' => 'tags',
            'placeholder' => 'Введите теги',
            'field_type' => 'input',
            'checks' => [],
        ],
    ],
];
