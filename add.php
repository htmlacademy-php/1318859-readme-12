<?php

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

echo '<pre>';
print_r($_POST);
echo '</pre>';

echo '<pre>';
print_r($_FILES);
echo '</pre>';

$title = 'readme: добавление публикации';
$types = get_post_types($con);
if (isset($_GET["type"])) {
    $type_name = $_GET["type"];
} else {
    $type_name = 'text';
}
$_GET["type"] = $type_name;

if (isset($_POST["send"])) {
    $type_name = $_POST["type"];
}

$required_fields = ['email', 'password', 'login'];
$errors = [];
$_FILES = null;
$rules = ['photo-url' => function () {
    if (empty($_FILES["userpic-file-photo"])) {
        if (!validateUrl('photo-url')) {
            return validateImageTypeFromUrl('photo-url');
        }
        return validateUrl('photo-url');
    }
    return false;
}, 'video-url' => function () {
    if (!validateUrl('video-url')) {
        return check_youtube_url($_POST['video-url']);
    }
    return validateUrl('video-url');
}, 'post-text' => function () {
    return validateFilled('post-text');
}, 'quote-text' => function () {
    return validateFilled('quote-text');
}, 'quote-author' => function () {
    return validateFilled('quote-author');
}, 'post-link' => function () {
    return validateUrl('post-link');
},];

foreach ($types as $type) {
    $class_name = $type['class_name'];
    $rules += [$class_name . '-heading' => function () {
        return validateFilled($class_name . '-heading');
    }];

    if (getPostVal($class_name . '-tags')) {
        getTags($class_name . '-tags');
    }
}


echo '<pre>';
print_r($rules);
echo '</pre>';

foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule();
    }
}

$errors = array_filter($errors);

echo '<pre>';
print_r($errors);
echo '</pre>';

//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    header("Location: /add.php?success=true");
//}


$main_content = include_template('adding-post.php', ['types' => $types, 'type_name' => $type_name,]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'user_name' => $user_name, 'title' => $title, 'is_auth' => $is_auth]);
?>

<?= $layout; ?>


