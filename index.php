<?php

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: популярное';

$types = get_post_types($con);

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $posts = get_filtered_posts($con, 't.id', $id);
} else {
    $id = '';
    $posts = get_filtered_posts($con, '', null);
}

$main_content = include_template('main.php', [
    'types' => $types,
    'posts' => $posts,
    'id' => $id
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => $title,
    'is_auth' => $is_auth
]);
?>

<?= $layout; ?>
