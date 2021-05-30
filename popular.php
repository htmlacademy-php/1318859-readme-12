<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: популярное';

$types = get_post_types($con);

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $posts = get_filtered_posts($con, 't.id', intval($id), NUMBER_OF_PAGE_POSTS);
} else {
    $id = '';
    $posts = get_filtered_posts($con, '', null, NUMBER_OF_PAGE_POSTS);
}

$main_content = include_template('main.php', [
    'types' => $types,
    'posts' => $posts,
    'id' => $id
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

?>
<?= $layout; ?>
