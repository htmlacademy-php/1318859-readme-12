<?php
include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: добавление публикации';
$types = get_post_types($con);
if (isset($_GET["type"])) {
    $current_tab = $_GET["type"];
} else {
    $current_tab = 'text';
}
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
            ], [
                'title' => 'Ссылка из интернета',
                'required' => false,
                'type' => 'url',
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
                'type' => 'url',
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($forms[$current_tab]['inputs'] as $input) {
        if (($input['type'] === 'text' || $input['type'] === 'textarea') && $input['required']) {
            $input['error'] = function($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            };
            $errors += [$current_tab . '-' . $input['name'] => $input['error']($current_tab, $input)];
        } elseif ($input['type'] === 'url') {
            if ($current_tab === 'photo') {
                $input['error'] = function($current_tab, $input) {
                    if (empty($_FILES["photo-userpic-file"]) || $_FILES["photo-userpic-file"]["error"]) {
                        if (!validateFilled($current_tab . '-' . $input['name'])) {
                            if (!validateUrl($current_tab . '-' . $input['name'])) {
                                return validateImageTypeFromUrl($current_tab . '-' . $input['name']);
                            }
                            return validateUrl($current_tab . '-' . $input['name']);
                        }
                        return "Укажите ссылку на картинку или загрузите файл с компьютера.";
                    }
                    return false;
                };
            } elseif ($current_tab === 'video') {
                $input['error'] = function ($current_tab, $input) {
                    if (!validateFilled($current_tab . '-' . $input['name'])) {
                        if (!validateUrl($current_tab . '-' . $input['name'])) {
                            return check_youtube_url($_POST[$current_tab . '-' . $input['name']]);
                        }
                        return validateUrl($current_tab . '-' . $input['name']);
                    }
                    return validateFilled($current_tab . '-' . $input['name']);
                };
            } else {
                $input['error'] = function ($current_tab, $input) {
                    if (!validateFilled($current_tab . '-' . $input['name'])) {
                        return validateUrl($current_tab . '-' . $input['name']);
                    }
                    return validateFilled($current_tab . '-' . $input['name']);
                };
            }
            $errors += [$current_tab . '-' . $input['name'] => $input['error']($current_tab, $input)];
        } elseif ($input['type'] === 'file') {
            $input['error'] = function($current_tab, $input) {
                if (!empty($_FILES[$current_tab . '-' . $input['name']]) && !$_FILES[$current_tab . '-' . $input['name']]["error"]) {
                    return validateImageType($current_tab . '-' . $input['name']);
                }
                return false;
            };
            $errors += [$current_tab . '-' . $input['name'] => $input['error']($current_tab, $input)];
        }
    }
    print_r($errors);

    if (isset($_POST["send"])) {
        $_GET["type"] = $_POST["type"];
    }

    if (empty($errors)) {
        foreach ($forms[$current_tab]['inputs'] as $input) {
            $db_post_title = $_POST[$current_tab . '-' . $input['name']];
            $bd_post_user_id = 1;
            $db_data = ['title' => $db_post_title, 'user_id' => $bd_post_user_id];
            if ($current_tab === 'photo') {
                if (getPostVal('photo-userpic-file')) {
                    $db_post_image = '/uploads/' . basename($_FILES['photo-userpic-file']['name']);
                } else {
                    $db_post_image = '/uploads' . strrchr($_POST['photo-url'], '/');
                }
                $db_data += ['image' => $db_post_image, 'type_id' => 1];
            } elseif ($current_tab === 'video') {
                $db_post_video = getPostVal('video-url');
                $db_data += ['video' => $db_post_video, 'type_id' => 2];
            } elseif ($current_tab === 'text') {
                $db_post_text_content = getPostVal('text-post');
                $db_data += ['text_content' => $db_post_text_content, 'type_id' => 3];
            } elseif ($current_tab === 'quote') {
                $db_post_text_content = getPostVal('quote-text');
                $db_post_quote_author = getPostVal('quote-author');
                $db_data += ['text_content' => $db_post_text_content, 'quote_author' => $db_post_quote_author, 'type_id' => 4];
            } else {
                $db_post_link = getPostVal('link-url');
                $db_data += ['link' => $db_post_link, 'type_id' => 5];
            }

            if (getPostVal($current_tab . '-tags')) {
                $post_tags = getTags($current_tab . '-tags');
                add_tags($con, $post_tags);
            }
        }
        $new_post_id = add_post($con, $db_data);

        header("Location: /post.php?id=$new_post_id");
    }
}

$main_content = include_template('adding-post.php', ['tabs' => $tabs, 'forms' => $forms, 'types' => $types, 'current_tab' => $current_tab, 'errors' => $errors,]);
$layout = include_template('layout.php', ['main_content' => $main_content, 'user_name' => $user_name, 'title' => $title, 'is_auth' => $is_auth]);
?>
<?= $layout; ?>


