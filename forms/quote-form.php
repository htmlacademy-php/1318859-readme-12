<?php
$form = [
    'title' => 'Форма добавления цитаты',
    'name' => 'quote-form',
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
            'title' => 'Текст цитаты',
            'required' => true,
            'type' => 'textarea',
            'name' => 'text',
            'placeholder' => 'Текст цитаты',
            'field_type' => 'textarea',
            'checks' => [
                0 => function ($current_tab, $input) {
                    return validateFilled($current_tab . '-' . $input['name']);
                }
            ]
        ],
        [
            'title' => 'Автор',
            'required' => true,
            'type' => 'text',
            'name' => 'author',
            'field_type' => 'input',
            'checks' => [
                0 => function ($current_tab, $input) {
                    return validateFilled($current_tab . '-' . $input['name']);
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
    ],
];
