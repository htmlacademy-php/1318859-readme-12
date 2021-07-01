<?php
return [
    'title' => 'Форма добавления текста',
    'name' => 'text-form',
    'inputs' => [
        [
            'title' => 'Заголовок',
            'required' => true,
            'type' => 'text',
            'name' => 'heading',
            'placeholder' => 'Введите заголовок',
            'field_type' => 'input',
            'max_length' => 200,
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
                },
                1 => function ($input, $configs) {
                    return validate_max_length($configs['current_tab'] . '-' . $input['name'], $input['max_length']);
                }
            ],
        ],
        [
            'title' => 'Текст поста',
            'required' => true,
            'type' => 'textarea',
            'name' => 'post',
            'placeholder' => 'Введите текст публикации',
            'field_type' => 'textarea',
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
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
            'max_length' => 50,
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_max_tag_name_length($configs['current_tab'] . '-' . $input['name'], $input['max_length']);
                }
            ],
        ],
    ],
];
