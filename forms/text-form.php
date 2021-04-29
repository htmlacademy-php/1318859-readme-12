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
        ],
        [
            'title' => 'Текст поста',
            'required' => true,
            'type' => 'textarea',
            'name' => 'post',
            'placeholder' => 'Введите текст публикации',
            'field_type' => 'textarea',
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
