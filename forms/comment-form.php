<?php
return [
    'title' => 'Форма добавления комментария',
    'name' => 'comment-form',
    'inputs' => [
        [
            'title' => 'Комментарий',
            'required' => false,
            'type' => 'textarea',
            'name' => 'comment',
            'placeholder' => 'Ваш комментарий',
            'field_type' => 'textarea',
            'checks' => [
                0 => function ($input) {
                    return validateFilled($input['name']);
                },
                1 => function ($input, $configs) {
                    return isCorrectMinLengthComment($input['name'], $configs['min_comment_length']);
                },
            ],
        ],
    ],
];
