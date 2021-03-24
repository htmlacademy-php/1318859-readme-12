<?php

//if (!isset($_SESSION['user'])) {
//    header("Location: /");
//    exit();
//}

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: страница результатов поиска';
$search = $_GET['q'] ?? '';

if (empty($search)) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

$posts = get_search_posts($con, $search);
print_r($posts);
if (!count($posts)) {
    $main_content = include_template('no-search-result.php');
}

$main_content = include_template('search-result.php', [
    'posts' => $posts,
]);

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);
?>
<?= $layout; ?>
