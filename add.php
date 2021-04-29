<?php

include_once 'config.php';
include_once 'init.php';
include_once 'form-config.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: добавление публикации';
$types = get_post_types($con);
$current_tab = (isset($_GET["type"])) ? $_GET["type"] : 'text';
$tabs = [
    'photo' => 'фото',
    'video' => 'видео',
    'text' => 'текст',
    'quote' => 'цитата',
    'link' => 'ссылка',
];
$form = include_once 'forms/' . $current_tab . '-form.php';

$_GET["type"] = $current_tab;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_form($form, $checks, $errors, $configs);

    if (isset($_POST["send"])) {
        $_GET["type"] = $_POST["type"];
    }

    if (empty($errors[$form['name']])) {
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

        $new_post_id = add_post($con, $db_data);

        if (isset($_POST[$current_tab . '-tags'])) {
            $post_tags = getTagsFromPost($current_tab . '-tags');
            $db_tags = get_all_tags($con);
            add_tags($con, $post_tags, $db_tags, $new_post_id);
        }

        header("Location: post.php?id=$new_post_id");
    }
}

$main_content = include_template('adding-post.php', [
    'tabs' => $tabs,
    'form' => $form,
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
