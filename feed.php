<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: моя лента';
$current_tab = (isset($_GET["type"])) ? htmlspecialchars($_GET["type"]) : '';

$tabs = [
    'photo' => 'фото',
    'video' => 'видео',
    'text' => 'текст',
    'quote' => 'цитата',
    'link' => 'ссылка',
];
$user_id = intval($_SESSION['user']['id']);
$posts = get_posts_of_following_users($con, $user_id);

if (empty($current_tab)) {
    $current_tab_posts = $posts;
} else {
    $current_tab_posts = [];
    $i = 0;
    foreach ($posts as $post) {
        if ($current_tab === $post['class_name']) {
            $current_tab_posts[$i] = $post;
            $i++;
        }
    }
}

$liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $user_id);
if (isset($_GET['liked_post_id'])) {
    toggle_like($con, $user_id, intval($_GET['liked_post_id']));
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$reposted_post_ids_by_session_user = get_all_reposted_post_ids_by_user($con, $user_id);
if (isset($_GET['reposted_post_id'])) {
    repost($con, intval($_GET['reposted_post_id']));
    header("Location: /profile.php?id=" . $user_id);
    exit();
}

$main_content = include_template('my-feed.php', [
    'current_tab_posts' => $current_tab_posts,
    'tabs' => $tabs,
    'user_id' => $user_id,
    'current_tab' => $current_tab,
    'con' => $con,
    'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
    'reposted_post_ids_by_session_user' => $reposted_post_ids_by_session_user,
]);

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'user_id' => $_SESSION['user']['id'],
    'title' => $title,
]);

echo $layout;
