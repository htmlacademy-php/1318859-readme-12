<?php
$form = [
    'title' => 'Форма добавления фото',
    'name' => 'photo-form',
    'inputs' => [
        [
            'title' => 'Заголовок',
            'required' => true,
            'type' => 'text',
            'name' => 'heading',
            'placeholder' => 'Введите заголовок',
            'field_type' => 'input',
            'checks' => [
                0 => function ($current_tab, $input) {
                    return validateFilled($current_tab . '-' . $input['name']);
                }
            ]
        ],
        [
            'title' => 'Ссылка из интернета',
            'required' => false,
            'type' => 'url',
            'name' => 'url',
            'placeholder' => 'Введите ссылку',
            'field_type' => 'input',
            'checks' => [
                0 => function ($current_tab, $input) {
                    return validateFilled($current_tab . '-' . $input['name']);
                },
                1 => function ($current_tab, $input) {
                    return validateUrl($current_tab . '-' . $input['name']);
                },
                2 => function ($current_tab, $input) {
                    return validateImageTypeFromUrl($current_tab . '-' . $input['name']);
                }
            ]
        ],
        [
            'title' => 'Теги',
            'required' => false,
            'type' => 'text',
            'name' => 'tags',
            'placeholder' => 'Введите теги',
            'field_type' => 'input',
        ],
        [
            'required' => false,
            'type' => 'file',
            'name' => 'userpic-file',
            'field_type' => 'input-file',
            'checks' => [
                0 => function ($current_tab, $input) {
                    return validateImageType($current_tab . '-' . $input['name']);
                }
            ]
        ]
    ],
];
