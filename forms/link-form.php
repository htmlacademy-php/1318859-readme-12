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
        ],
        [
            'title' => 'Ссылка',
            'required' => true,
            'type' => 'url',
            'name' => 'url',
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
    ],
];
