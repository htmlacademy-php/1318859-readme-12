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
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
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
            'checks' => [
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
                },
                1 => function ($input, $configs) {
                    return validate_url($configs['current_tab'] . '-' . $input['name']);
                },
                2 => function ($input, $configs) {
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
