<?php
return [
    'title' => 'Форма добавления видео',
    'name' => 'video-form',
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
            'title' => 'Ссылка youtube',
            'required' => true,
            'type' => 'url',
            'name' => 'url',
            'placeholder' => 'Введите ссылку',
            'field_type' => 'input',
            'max_length' => 250,
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
                },
                1 => function ($input, $configs) {
                    return validate_max_length($configs['current_tab'] . '-' . $input['name'], $input['max_length']);
                },
                2 => function ($input, $configs) {
                    return validate_url($configs['current_tab'] . '-' . $input['name']);
                },
                3 => function ($input, $configs) {
                    return check_youtube_url($_POST[$configs['current_tab'] . '-' . $input['name']]);
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
