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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rules = ['photo-url' => function () {
        if (empty($_FILES["photo-userpic-file"]) || $_FILES["photo-userpic-file"]["error"]) {
            if (!validateFilled('photo-url')) {
                if (!validateUrl('photo-url')) {
                    return validateImageTypeFromUrl('photo-url');
                }
                return validateUrl('photo-url');
            }
            return "Укажите ссылку на картинку или загрузите файл с компьютера.";
        }
        return false;
    }, 'photo-userpic-file' => function () {
        if (!empty($_FILES["photo-userpic-file"]) && !$_FILES["photo-userpic-file"]["error"]) {
            return validateImageType('photo-userpic-file');
        }
        return false;
    }, 'video-url' => function () {
        if (!validateFilled('video-url')) {
            if (!validateUrl('video-url')) {
                return check_youtube_url($_POST['video-url']);
            }
            return validateUrl('video-url');
        }
        return validateFilled('video-url');
    }, 'text-post' => function () {
        return validateFilled('text-post');
    }, 'quote-text' => function () {
        return validateFilled('quote-text');
    }, 'quote-author' => function () {
        return validateFilled('quote-author');
    }, 'link-url' => function () {
        if (!validateFilled('link-url')) {
            return validateUrl('link-url');
        }
        return validateFilled('link-url');
    }, 'photo-heading' => function () {
        return validateFilled('photo-heading');
    }, 'video-heading' => function () {
        return validateFilled('video-heading');
    }, 'text-heading' => function () {
        return validateFilled('text-heading');
    }, 'quote-heading' => function () {
        return validateFilled('quote-heading');
    }, 'link-heading' => function () {
        return validateFilled('link-heading');
    },];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }
    foreach ($_FILES as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    if (isset($_POST["send"])) {
        $current_tab = $_POST["type"];
    }

foreach ($types as $type) {
    $class_name = $type['class_name'];
    $rules += [$type['class_name'] . '-heading' => function ($class_name) {
        return validateHeading($class_name);
    }];
}

    if (!$errors) {
        foreach ($types as $type) {
            $class_name = $type['class_name'];
            $db_post_title = getPostVal($_POST['type'] . '-heading');
            $bd_post_user_id = 1;
            $db_data = ['title' => $db_post_title, 'user_id' => $bd_post_user_id];
            if ($_POST['type'] === 'photo') {
                if (getPostVal('photo-userpic-file')) {
                    $db_post_image = '/uploads/' . basename($_FILES['photo-userpic-file']['name']);
                } else {
                    $db_post_image = '/uploads' . strrchr($_POST['photo-url'], '/');
                }
                $db_data += ['image' => $db_post_image, 'type_id' => 1];
            } elseif ($_POST['type'] === 'video') {
                $db_post_video = getPostVal('video-url');
                $db_data += ['video' => $db_post_video, 'type_id' => 2];
            } elseif ($_POST['type'] === 'text') {
                $db_post_text_content = getPostVal('text-post');
                $db_data += ['text_content' => $db_post_text_content, 'type_id' => 3];
            } elseif ($_POST['type'] === 'quote') {
                $db_post_text_content = getPostVal('quote-text');
                $db_post_quote_author = getPostVal('quote-author');
                $db_data += ['text_content' => $db_post_text_content, 'quote_author' => $db_post_quote_author, 'type_id' => 4];
            } else {
                $db_post_link = getPostVal('link-url');
                $db_data += ['link' => $db_post_link, 'type_id' => 5];
            }

            if (getPostVal($class_name . '-tags')) {
                $post_tags = getTags($class_name . '-tags');
                add_tags($con, $post_tags);
            }
        }
        $new_post_id = add_post($con, $db_data);

        header("Location: /post.php?id=$new_post_id");
    }
}

$main_content = include_template('adding-post.php', ['types' => $types, 'current_tab' => $current_tab, 'errors' => $errors,]);
$layout = include_template('layout.php', ['main_content' => $main_content, 'user_name' => $user_name, 'title' => $title, 'is_auth' => $is_auth]);
?>
<?= $layout; ?>


