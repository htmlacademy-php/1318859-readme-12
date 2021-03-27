<?php

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: добавление публикации';
$types = get_post_types($con);

$current_tab = (isset($_GET["type"])) ? $_GET["type"] : 'text';

$_GET["type"] = $current_tab;
$errors = [];

$tabs = [
    'photo' => 'фото',
    'video' => 'видео',
    'text' => 'текст',
    'quote' => 'цитата',
    'link' => 'ссылка',
];

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
                'checks' => [
                    0 => function ($current_tab, $input) {
                        return validateFilled($current_tab . '-' . $input['name']);
                    }
                ]
            ],
            [
                'title' => 'Ссылка youtube',
                'required' => true,
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
                        return check_youtube_url($_POST[$current_tab . '-' . $input['name']]);
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
                'checks' => [
                    0 => function ($current_tab, $input) {
                        return validateFilled($current_tab . '-' . $input['name']);
                    }
                ]
            ],
            [
                'title' => 'Текст поста',
                'required' => true,
                'type' => 'textarea',
                'name' => 'post',
                'placeholder' => 'Введите текст публикации',
                'field_type' => 'textarea',
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
                'checks' => [
                    0 => function ($current_tab, $input) {
                        return validateFilled($current_tab . '-' . $input['name']);
                    }
                ]
            ],
            [
                'title' => 'Ссылка',
                'required' => true,
                'type' => 'url',
                'name' => 'url',
                'field_type' => 'input',
                'checks' => [
                    0 => function ($current_tab, $input) {
                        return validateFilled($current_tab . '-' . $input['name']);
                    },
                    1 => function ($current_tab, $input) {
                        return validateUrl($current_tab . '-' . $input['name']);
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
    ],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($forms[$current_tab]['inputs'] as $input) {
        if ($input['required']) {
            foreach ($input['checks'] as $check) {
                if ($check($current_tab, $input)) {
                    $errors += [$current_tab . '-' . $input['name'] => $check($current_tab, $input)];
                }
            }
        } elseif ($current_tab === 'photo' && $input['type'] === 'url') {
            if (empty($_FILES["photo-userpic-file"]) || $_FILES["photo-userpic-file"]["error"] === 4) {
                foreach ($input['checks'] as $check) {
                    if ($check($current_tab, $input)) {
                        $errors += [$current_tab . '-' . $input['name'] => $check($current_tab, $input)];
                    }
                }
            }
        } elseif ($input['type'] === 'file') {
            if (!empty($_FILES[$current_tab . '-' . $input['name']]) && $_FILES[$current_tab . '-' . $input['name']]["error"] !== 4) {
                foreach ($input['checks'] as $check) {
                    if ($check($current_tab, $input)) {
                        $errors += [$current_tab . '-' . $input['name'] => $check($current_tab, $input)];
                    }
                }
            }
        }
    }

    if (isset($_POST["send"])) {
        $_GET["type"] = $_POST["type"];
    }

    if (empty($errors)) {
        $db_post_title = $_POST[$current_tab . '-heading'];
        $bd_post_user_id = $_SESSION['user']['id'];
        $db_data = [
            'title' => $db_post_title,
            'user_id' => $bd_post_user_id
        ];
        if ($current_tab === 'photo') {
            if (isset($_POST['photo-userpic-file'])) {
                $db_post_image = '/uploads/' . basename($_FILES['photo-userpic-file']['name']);
            } else {
                $db_post_image = '/uploads' . strrchr($_POST['photo-url'], '/');
            }
            $db_data += [
                'image' => $db_post_image,
                'type_id' => 1
            ];
        } elseif ($current_tab === 'video') {
            $db_post_video = $_POST['video-url'];
            $db_data += [
                'video' => $db_post_video,
                'type_id' => 2
            ];
        } elseif ($current_tab === 'text') {
            $db_post_text_content = $_POST['text-post'];
            $db_data += [
                'text_content' => $db_post_text_content,
                'type_id' => 3
            ];
        } elseif ($current_tab === 'quote') {
            $db_post_text_content = $_POST['quote-text'];
            $db_post_quote_author = $_POST['quote-author'];
            $db_data += [
                'text_content' => $db_post_text_content,
                'quote_author' => $db_post_quote_author,
                'type_id' => 4
            ];
        } else {
            $db_post_link = $_POST['link-url'];
            $db_data += [
                'link' => $db_post_link,
                'type_id' => 5
            ];
        }

        if (isset($_POST[$current_tab . '-tags'])) {
            $post_tags = getTagsFromPost($current_tab . '-tags');
            $db_tags = get_all_tags($con);
            add_tags($con, $post_tags, $db_tags);
        }
        $new_post_id = add_post($con, $db_data);

        header("Location: post.php?id=$new_post_id");
    }
}

$main_content = include_template('adding-post.php', [
    'tabs' => $tabs,
    'forms' => $forms,
    'types' => $types,
    'current_tab' => $current_tab,
    'errors' => $errors,
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);
?>
<?= $layout; ?>


