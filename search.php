<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
if (isset($_GET['liked_post_id'])) {
    toggle_like($con, $_SESSION['user']['id'], $_GET['liked_post_id']);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$title = 'readme: страница результатов поиска';
$search = trim($_GET['q']) ?? '';
$firstSymbol = substr($search, 0, 1);
if (empty($search)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

if (isset($_GET['type']) && $_GET['type'] === 'tag') {
    $posts = get_posts_with_tag($con, $search);
    $result_text = '#' . $search;
    $search_line_text = '';
} else {
    if ($firstSymbol === '#') {

        $posts = get_posts_with_tag($con, substr($search, 1));
        $result_text = $search;
        $search_line_text = '';
    } else {
        $posts = get_search_posts($con, $search);
        $result_text = $search;
        $search_line_text = $search;
    }
}

if (!count($posts)) {
    $main_content = include_template('no-search-results.php', [
        'result_text' => $result_text,
    ]);
} else {
    $main_content = include_template('search-results.php', [
        'con' => $con,
        'posts' => $posts,
        'search' => $search,
        'result_text' => $result_text,
        'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
    ]);
}

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
    'search' => $search,
    'search_line_text' => $search_line_text,
]);

echo $layout;
