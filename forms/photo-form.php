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
        ],
        [
            'title' => 'Ссылка из интернета',
            'required' => false,
            'type' => 'url',
            'name' => 'url',
            'placeholder' => 'Введите ссылку',
            'field_type' => 'input',
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
        ]
    ],
];
