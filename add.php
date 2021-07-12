<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: добавление публикации';
$types = get_post_types($con);
$tabs = [
    'photo' => 'фото',
    'video' => 'видео',
    'text'  => 'текст',
    'quote' => 'цитата',
    'link'  => 'ссылка',
];
$current_tab = 'text';

foreach (array_keys($tabs) as $type) {
    if (isset($_GET["type"]) && $_GET["type"] === $type) {
        $current_tab = $_GET["type"];
        break;
    }
}

$form = include_once 'forms/' . $current_tab . '-form.php';

$_GET["type"] = $current_tab;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_form($form, $configs);
    if (isset($_POST["send"])) {
        $_GET["type"] = htmlspecialchars($_POST["type"]);
    }

    if (empty($errors[$form['name']])) {
        $db_post_title = $_POST[$current_tab . '-heading'];
        $bd_post_user_id = $_SESSION['user']['id'];
        $db_data = [
            'title'   => $db_post_title,
            'user_id' => $bd_post_user_id,
        ];
        build_post_data($current_tab, $db_data);
        $new_post = add_post($con, $db_data);
        $new_post_id = $new_post['id'];
        $user_followers = get_followers($con, $_SESSION['user']['id']);
        send_new_post_notification($new_post, $user_followers);

        if (isset($_POST[$current_tab . '-tags'])) {
            $post_tags = get_tags_from_post($current_tab . '-tags');
            $db_tags = get_all_tags($con);
            add_tags($con, $post_tags, $db_tags, $new_post_id);
        }

        header("Location: post.php?id=$new_post_id");
    }
}

$main_content = include_template('adding-post.php', [
    'tabs'        => $tabs,
    'form'        => $form,
    'types'       => $types,
    'current_tab' => $current_tab,
    'errors'      => $errors ?? '',
]);
$layout = include_template('layout.php', [
    'main_content'                       => $main_content,
    'user_name'                          => $_SESSION['user']['login'],
    'user_avatar'                        => $_SESSION['user']['avatar'],
    'user_id'                            => $_SESSION['user']['id'],
    'title'                              => $title,
    'nav_links'                          => $configs['nav_links'],
    'count_session_user_unread_messages' => count_user_unread_messages($con, intval($_SESSION['user']['id'])),
]);

echo $layout;
