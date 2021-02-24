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
            ], [
                'title' => 'Ссылка из интернета',
                'required' => false,
                'type' => 'text',
                'name' => 'url',
                'placeholder' => 'Введите ссылку',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
            ], [
                'title' => 'Перетащите фото сюда',
                'required' => false,
                'type' => 'file',
                'name' => 'userpic-file',
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
            ], [
                'title' => 'Ссылка youtube',
                'required' => true,
                'type' => 'text',
                'name' => 'url',
                'placeholder' => 'Введите ссылку',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
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
            ], [
                'title' => 'Текст поста',
                'required' => true,
                'type' => 'textarea',
                'name' => 'post',
                'placeholder' => 'Введите текст публикации',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
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
            ], [
                'title' => 'Текст цитаты',
                'required' => true,
                'type' => 'textarea',
                'name' => 'text',
                'placeholder' => 'Текст цитаты',
            ], [
                'title' => 'Автор',
                'required' => true,
                'type' => 'text',
                'name' => 'author',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
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
            ], [
                'title' => 'Ссылка',
                'required' => true,
                'type' => 'url',
                'name' => 'url',
            ], [
                'title' => 'Теги',
                'required' => false,
                'type' => 'text',
                'name' => 'tags',
                'placeholder' => 'Введите теги',
            ],
        ],
    ],
];

?>
<!--2. Получаем все поля формы-->
<!--3. Проверяем каждое поле этой формы-->
