<?php

//if (!isset($_SESSION['user'])) {
//    header("Location: /");
//    exit();
//}

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: страница результатов поиска';
$search = trim($_GET['q']) ?? '';

if (empty($search)) {
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}

if ($_GET['type'] === 'tag') {
    $post_ids = get_posts_with_tag($con, $search);
    $posts = [];
    foreach ($post_ids as $post_id) {
        $post = find_posts_with_tag($con, $post_id['post_id']);
        $posts += $post;
    }

    $result_text = '#' . $search;
    $search_line_text = '';
} else {
    $posts = get_search_posts($con, $search);
    $result_text = $search;
    $search_line_text = $search;
}

if (!count($posts)) {
    $main_content = include_template('no-search-results.php', [
        'result_text' => $result_text,
    ]);
} else {
    $main_content = include_template('search-results.php', [
        'posts' => $posts,
        'result_text' => $result_text,
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
?>
<?= $layout; ?>
