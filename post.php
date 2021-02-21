<?php
include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: публикация';
$posts_id = get_posts_id($con);

$existed_ids = [];

    for ($i = 0; $i < count($posts_id); $i++) {
        $existed_ids[$i] = $posts_id[$i]['id'];
    }

if (isset($_GET["id"]) && in_array($_GET["id"], $existed_ids)) {
    $id = $_GET["id"];
    $post = get_post($con, 'p.id', $id);
    $author_post_id = $post['user_id'];
    $author = get_user($con, $author_post_id);
    $amount_of_author_posts = count(get_filtered_posts($con, 'u.id', $author_post_id));
    $number_of_author_followers = count(get_followers($con, $author_post_id));
    $main_content = include_template('post-detail.php', ['post' => $post, 'id' => $id, 'amount_of_author_posts' => $amount_of_author_posts, 'author' => $author, 'number_of_author_followers' => $number_of_author_followers]);
} else {
    header('HTTP/1.0 404 not found');
    $main_content = include_template('404.php');
}

$layout = include_template('layout.php', ['main_content' => $main_content, 'user_name' => $user_name, 'title' => $title, 'is_auth' => $is_auth]);
?>
<?= $layout; ?>
