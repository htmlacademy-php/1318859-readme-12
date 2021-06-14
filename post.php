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

    $liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
    if (isset($_GET['liked'])) {
        toggle_like($con, $_SESSION['user']['id'], $id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $reposted_post_ids_by_session_user = get_all_reposted_post_ids_by_user($con, $_SESSION['user']['id']);
    if (isset($_GET['reposted'])) {
        repost($con, $id);
        header("Location: /profile.php?id=" . $_SESSION['user']['id']);
        exit();
    }

    add_view($con, $id);
    $post = get_post($con, 'p.id', $id);
    $selfPage = ($post['user_id'] === intval($_SESSION['user']['id']));
    $author_post_id = $post['user_id'];
    $author = get_user($con, $author_post_id);
    $amountOfUserPosts = count(get_filtered_posts($con, 'u.id', $author_post_id, null));
    $amountOfUserFollowers = count(get_followers($con, $author_post_id));
    $post_tags = get_post_tags($con, $id);

    $followersOfUser = get_followers($con, $author['id']);
    if (isset($_GET['subscribed'])) {
        add_follower($con, $_SESSION['user'], $author);
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }
    if (isset($_GET['unsubscribed'])) {
        remove_follower($con, $_SESSION['user']['id'], $author['id']);
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }
    $subscribe = false;
    foreach ($followersOfUser as $follower) {
        if ($follower['id'] === intval($_SESSION['user']['id'])) {
            $subscribe = true;
        }
    }

    $viewsCount = intval($post['views_count']);
    $form = include_once 'forms/comment-form.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validate_form($form, $configs);
        if (empty($errors[$form['name']])) {
            $db_content = $_POST['comment'];
            $db_user_id = intval($_SESSION['user']['id']);
            $db_post_id = intval($_POST['id']);
            $db_data = [
                'content' => $db_content,
                'user_id' => $db_user_id,
                'post_id' => $db_post_id,
            ];
            add_comment($con, $db_data);
            header("Location: profile.php?id=$db_user_id");
        }
    }

    $postComments = get_post_comments($con, $id);
    $countOfPostComments = count($postComments);
    $countOfShownPostComments = min($countOfPostComments, NUMBER_OF_SHOWN_POST_COMMENTS);

    $main_content = include_template('post-detail.php', [
        'con' => $con,
        'post' => $post,
        'form' => $form,
        'errors' => $errors ?? '',
        'selfPage' => $selfPage,
        'id' => $id,
        'amountOfUserPosts' => $amountOfUserPosts,
        'author' => $author,
        'amountOfUserFollowers' => $amountOfUserFollowers,
        'post_tags' => $post_tags,
        'subscribe' => $subscribe,
        'viewsCount' => $viewsCount,
        'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
        'postComments' => $postComments,
        'countOfPostComments' => $countOfPostComments,
        'countOfShownPostComments' => $countOfShownPostComments,
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
