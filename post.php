<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: публикация';
$posts = get_all_posts($con);
$existed_ids = [];

for ($i = 0; $i < count($posts); $i++) {
    $existed_ids[$i] = $posts[$i]['id'];
}

if (isset($_GET["id"]) && in_array($_GET["id"], $existed_ids)) {
    $id = $_GET["id"];
    $post = get_post($con, 'p.id', $id);
    $author_post_id = $post['user_id'];
    $author = get_user($con, $author_post_id);
    $amount_of_author_posts = count(get_filtered_posts($con, 'u.id', $author_post_id));
    $number_of_author_followers = count(get_followers($con, $author_post_id));
    $post_tags = get_post_tags($con, $id);
    $main_content = include_template('post-detail.php', [
        'post' => $post,
        'id' => $id,
        'amount_of_author_posts' => $amount_of_author_posts,
        'author' => $author,
        'number_of_author_followers' => $number_of_author_followers,
        'post_tags' => $post_tags,
    ]);
} else {
    header('HTTP/1.0 404 not found');
    $main_content = include_template('404.php');
}

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);
?>
<?= $layout; ?>
