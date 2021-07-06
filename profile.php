<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: профиль';

$tabs = ['posts' => 'посты', 'likes' => 'лайки', 'follows' => 'подписки',];

foreach ($tabs as $type => $name) {
    if (isset($_GET["tab"]) && $_GET["tab"] === $type) {
        $current_tab = $_GET["tab"];
        break;
    }
    $current_tab = 'posts';
}

$users = get_all_users($con);
$existed_ids = [];

for ($i = 0; $i < count($users); $i++) {
    $existed_ids[$i] = $users[$i]['id'];
}

if (isset($_GET["id"]) && in_array($_GET["id"], $existed_ids)) {
    $user = get_user($con, intval($_GET['id']));
    $self_page = (intval($_GET['id']) === intval($_SESSION['user']['id']));

    $amount_of_user_posts = count(get_filtered_posts($con, 'u.id', $user['id'], null));
    $amount_of_user_followers = count(get_followers($con, $user['id']));
    $user_posts = get_posts_of_user($con, $user['id']);
    foreach ($user_posts as $i => $post) {
        $user_posts[$i]['post_tags'] = get_post_tags($con, $post['id']);
    }

    $liked_posts_of_user = get_liked_posts_of_user($con, $user['id']);
    $following_users_of_user = get_following_users_of_user($con, $user['id']);
    $followers_of_user = get_followers($con, $user['id']);
    $following_users_of_session_user = get_following_users_of_user($con, intval($_SESSION['user']['id']));

    foreach ($followers_of_user as $i => $follower) {
        $followers_of_user[$i]['amount_of_posts']
            = count(get_filtered_posts($con, 'u.id', $follower['id'], null));
        $followers_of_user[$i]['amount_of_followers']
            = count(get_followers($con, $follower['id']));
        foreach ($following_users_of_session_user as $following_user) {
            if ($following_user['id'] === $follower['id']) {
                $followers_of_user[$i]['subscribed_by_session_user'] = true;
                break;
            } else {
                $followers_of_user[$i]['subscribed_by_session_user'] = false;
            }
        }
    }

    $liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
    if (isset($_GET['liked_post_id'])) {
        toggle_like($con, intval($_SESSION['user']['id']), intval($_GET['liked_post_id']));
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $reposted_post_ids_by_session_user = get_all_reposted_post_ids_by_user($con, $_SESSION['user']['id']);
    if (isset($_GET['reposted_post_id'])) {
        repost($con, intval($_GET['reposted_post_id']));
        header("Location: /profile.php?id=" . $user['id']);
        exit();
    }

    if (isset($_GET['subscribed'])) {
        add_follower($con, $_SESSION['user'], $user);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    if (isset($_GET['unsubscribed'])) {
        remove_follower($con, $_SESSION['user']['id'], $user['id']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $subscribe = false;
    foreach ($followers_of_user as $follower) {
        if ($follower['id'] === intval($_SESSION['user']['id'])) {
            $subscribe = true;
        }
    }

    $main_content = include_template('profile.php', [
        'user_posts'                        => $user_posts,
        'tabs'                              => $tabs,
        'user'                              => $user,
        'self_page'                         => $self_page,
        'current_tab'                       => $current_tab,
        'amount_of_user_posts'              => $amount_of_user_posts,
        'amount_of_user_followers'          => $amount_of_user_followers,
        'liked_posts_of_user'               => $liked_posts_of_user,
        'following_users_of_user'           => $following_users_of_user,
        'followers_of_user'                 => $followers_of_user,
        'subscribe'                         => $subscribe,
        'liked_post_ids_by_session_user'    => $liked_post_ids_by_session_user,
        'reposted_post_ids_by_session_user' => $reposted_post_ids_by_session_user,
        'con'                               => $con,
    ]);
} else {
    header('HTTP/1.0 404 not found');
    $main_content = include_template('404.php');
}

$layout = include_template('layout.php', [
    'main_content'                       => $main_content,
    'user_name'                          => $_SESSION['user']['login'],
    'user_avatar'                        => $_SESSION['user']['avatar'],
    'user_id'                            => $_SESSION['user']['id'],
    'title'                              => $title,
    'nav_links'                          => $configs['nav_links'],
    'count_session_user_unread_messages' => count_user_unread_messages($con, intval($_SESSION['user']['id'])),
]);

echo $layout;
