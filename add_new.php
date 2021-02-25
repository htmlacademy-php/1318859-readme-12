<!--1. Готовим массив элементов форм-->
<?php
$forms = [
    'photo' => [
        'title' => 'Форма добавления фото',
        'inputs' => [
            [
                'title' => 'Заголовок',
                'required' => true,
                'type' => 'text',
                'name' => 'heading',
                'placeholder' => 'Введите заголовок',
                'field_type' => 'input',
            ], [
                'title' => 'Ссылка из интернета',
                'required' => false,
                'type' => 'text',
                'name' => 'url',
                'placeholder' => 'Введите ссылку',
                'field_type' => 'input',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
                'field_type' => 'input',
            ], [
                'required' => false,
                'type' => 'file',
                'name' => 'userpic-file',
                'field_type' => 'input-file',
            ]
        ],
    ],
    'video' => [
        'title' => 'Форма добавления видео',
        'inputs' => [
            [
                'title' => 'Заголовок',
                'required' => true,
                'type' => 'text',
                'name' => 'heading',
                'placeholder' => 'Введите заголовок',
                'field_type' => 'input',
            ], [
                'title' => 'Ссылка youtube',
                'required' => true,
                'type' => 'text',
                'name' => 'url',
                'placeholder' => 'Введите ссылку',
                'field_type' => 'input',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
                'field_type' => 'input',
            ],
        ],
    ],
    'text' => [
        'title' => 'Форма добавления текста',
        'inputs' => [
            [
                'title' => 'Заголовок',
                'required' => true,
                'type' => 'text',
                'name' => 'heading',
                'placeholder' => 'Введите заголовок',
                'field_type' => 'input',
            ], [
                'title' => 'Текст поста',
                'required' => true,
                'type' => 'textarea',
                'name' => 'post',
                'placeholder' => 'Введите текст публикации',
                'field_type' => 'textarea',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
                'field_type' => 'input',
            ],
        ],
    ],
    'quote' => [
        'title' => 'Форма добавления цитаты',
        'inputs' => [
            [
                'title' => 'Заголовок',
                'required' => true,
                'type' => 'text',
                'name' => 'heading',
                'placeholder' => 'Введите заголовок',
                'field_type' => 'input',
            ], [
                'title' => 'Текст цитаты',
                'required' => true,
                'type' => 'textarea',
                'name' => 'text',
                'placeholder' => 'Текст цитаты',
                'field_type' => 'textarea',
            ], [
                'title' => 'Автор',
                'required' => true,
                'type' => 'text',
                'name' => 'author',
                'field_type' => 'input',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
                'field_type' => 'input',
            ],
        ],
    ],
    'link' => [
        'title' => 'Форма добавления ссылки',
        'inputs' => [
            [
                'title' => 'Заголовок',
                'required' => true,
                'type' => 'text',
                'name' => 'heading',
                'placeholder' => 'Введите заголовок',
                'field_type' => 'input',
            ], [
                'title' => 'Ссылка',
                'required' => true,
                'type' => 'url',
                'name' => 'url',
                'field_type' => 'input',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
                'field_type' => 'input',
            ],
        ],
    ],
];

?>
<!--2. Получаем все поля формы-->
<!--3. Проверяем каждое поле этой формы-->
