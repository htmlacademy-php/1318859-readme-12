<?php
include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: публикация';
$amount_of_posts = count(get_all_posts($con));

if (isset($_GET["id"]) && $_GET["id"] <= $amount_of_posts) {
    $id = $_GET["id"];
//    $post = get_post($con, 'p.id', $id);
    $post = get_post($con, 'p.id', $id);
    $author_post_id = $post['user_id'];
    $author = get_user($con, $author_post_id);
    $amount_of_author_posts = count(get_filtered_posts($con, 'u.id', $author_post_id));
    $number_of_author_followers = count(get_followers($con, $author_post_id));
    $main_content = include_template('post-detail.php', [
        'post' => $post,
        'id' => $id,
        'amount_of_author_posts' => $amount_of_author_posts,
        'author' => $author,
        'number_of_author_followers' => $number_of_author_followers
    ]);
} else {
    header('HTTP/1.0 404 not found');
    $main_content = include_template('404.php');
}

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => $title,
    'is_auth' => $is_auth
]);
?>
<?= $layout; ?>
