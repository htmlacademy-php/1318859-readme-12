<?php

//if (!isset($_SESSION['user'])) {
//    header("Location: /");
//    exit();
//}

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: страница результатов поиска';
$search = trim($_GET['q']) ?? '';

if (empty($search)) {
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}

$posts = get_search_posts($con, $search);
echo '<pre>';
print_r($posts);
echo '</pre>';
if (!count($posts)) {
    $main_content = include_template('no-search-results.php', [
        'search' => $search,
    ]);
} else {
    $main_content = include_template('search-results.php', [
        'posts' => $posts,
        'search' => $search,
    ]);
}

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
    'search' => $search,
]);
?>
<?= $layout; ?>
