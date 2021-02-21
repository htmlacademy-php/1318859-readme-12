<?php
include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';
//
//echo '<pre>';
//print_r($_FILES);
//echo '</pre>';

$title = 'readme: добавление публикации';
$types = get_post_types($con);
if (isset($_GET["type"])) {
    $type_name = $_GET["type"];
} else {
    $type_name = 'text';
}
$_GET["type"] = $type_name;

//$required_fields = ['email', 'password', 'login'];
$errors = [];
$rules = ['photo-url' => function () {
    if (empty($_FILES["userpic-file-photo"]) || $_FILES["userpic-file-photo"]["error"]) {
        if (!validateFilled('photo-url')) {
            if (!validateUrl('photo-url')) {
                return validateImageTypeFromUrl('photo-url');
            }
            return validateUrl('photo-url');
        }
        return "Укажите ссылку на картинку или загрузите файл с компьютера.";
    }
    return false;
}, 'userpic-file-photo' => function () {
    if (!empty($_FILES["userpic-file-photo"]) && !$_FILES["userpic-file-photo"]["error"]) {
        return validateImageType('userpic-file-photo');
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
}, 'post-text' => function () {
    return validateFilled('post-text');
}, 'quote-text' => function () {
    return validateFilled('quote-text');
}, 'quote-author' => function () {
    return validateFilled('quote-author');
}, 'post-link' => function () {
    if (!validateFilled('post-link')) {
        return validateUrl('post-link');
    }
    return validateFilled('post-link');
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

//echo '<pre>';
//print_r($errors);
//echo '</pre>';

if (isset($_POST["send"])) {
    $type_name = $_POST["type"];
}

//foreach ($types as $type) {
//    $class_name = $type['class_name'];
//    $rules += [$type['class_name'] . '-heading' => function () {
//        return validateHeading($class_name);
//    }];
//}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$errors) {

    foreach ($types as $type) {
        $class_name = $type['class_name'];
        $db_post_title = getPostVal($_POST['type'] . '-heading');
        $bd_post_user_id = 1;
        $db_data = ['title' => $db_post_title, 'user_id' => $bd_post_user_id];
        if ($_POST['type'] === 'photo') {
            if (getPostVal('userpic-file-photo')) {
                $db_post_image = '/uploads/' . basename($_FILES['userpic-file-photo']['name']);
                } else {
                $db_post_image = '/uploads' . strrchr($_POST['photo-url'], '/');
            }
            $db_data += ['image' => $db_post_image, 'type_id' => 1];
        } elseif ($_POST['type'] === 'video') {
            $db_post_video = getPostVal('video-url');
            $db_data += ['video' => $db_post_video, 'type_id' => 2];
        } elseif ($_POST['type'] === 'text') {
            $db_post_text_content = getPostVal('post-text');
            $db_data += ['text_content' => $db_post_text_content, 'type_id' => 3];
        } elseif ($_POST['type'] === 'quote') {
            $db_post_text_content = getPostVal('quote-text');
            $db_post_quote_author = getPostVal('quote-author');
            $db_data += ['text_content' => $db_post_text_content, 'quote_author' => $db_post_quote_author, 'type_id' => 4];
        } else {
            $db_post_link = getPostVal('post-link');
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


$main_content = include_template('adding-post.php', ['types' => $types, 'type_name' => $type_name, 'errors' => $errors,]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'user_name' => $user_name, 'title' => $title, 'is_auth' => $is_auth]);
?>

<?= $layout; ?>


