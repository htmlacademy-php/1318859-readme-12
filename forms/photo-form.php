<?php
return [
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
                0 => function ($input, $configs) {
                    return validate_filled($configs['current_tab'] . '-' . $input['name']);
                }
            ],
        ],
        [
            'title' => 'Ссылка из интернета',
            'required' => false,
            'type' => 'url',
            'name' => 'url',
            'placeholder' => 'Введите ссылку',
            'field_type' => 'input',
            'checks' => (empty($_FILES["photo-userpic-file"]) || $_FILES["photo-userpic-file"]["error"] === 4) ? [
            0 => function ($input, $configs) {
                return validate_filled($configs['current_tab'] . '-' . $input['name']);
            },
            1 => function ($input, $configs) {
                return validate_url($configs['current_tab'] . '-' . $input['name']);
            },
            2 => function ($input, $configs) {
                return validate_image_type_from_url($configs['current_tab'] . '-' . $input['name']);
            }
        ] : [],
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
        [
            'required' => false,
            'type' => 'file',
            'name' => 'userpic-file',
            'field_type' => 'input-file',
            'checks' => (!empty($_FILES['photo-userpic-file']) && $_FILES['photo-userpic-file']["error"] !== 4) ? [
                0 => function ($input, $configs) {
                    return validate_image_type($configs['current_tab'] . '-' . $input['name']);
                }
            ] : [],
        ]
    ],
];
