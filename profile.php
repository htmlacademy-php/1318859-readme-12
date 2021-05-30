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
$currentTab = (isset($_GET["tab"])) ? $_GET["tab"] : 'posts';

$tabs = [
    'posts' => 'посты',
    'likes' => 'лайки',
    'follows' => 'подписки',
];
$user = get_user($con, $_GET['id']);
$selfPage = ($_GET['id'] === $_SESSION['user']['id']);

$amountOfUserPosts = count(get_filtered_posts($con, 'u.id', $user['id'], null));
$amountOfUserFollowers = count(get_followers($con, $user['id']));
$userPosts = get_posts_of_user($con, $user['id']);
foreach ($userPosts as $i => $post) {
    $userPosts[$i]['post_tags'] = get_post_tags($con, $post['id']);
}

$likedPostsOfUser = get_liked_posts_of_user($con, $user['id']);
$followingUsersOfUser = get_following_users_of_user($con, $user['id']); //те, на кого подписан пользователь
$followersOfUser = get_followers($con, $user['id']); // подписчики пользователя
$followingUsersOfSessionUser = get_following_users_of_user($con, intval($_SESSION['user']['id'])); // подписчики пользователя

/*echo '<pre>';
print_r('userPosts');
print_r($userPosts);
echo '</pre>';*/

foreach ($followersOfUser as $i => $follower) {
    $followersOfUser[$i]['amount_of_posts'] = count(get_filtered_posts($con, 'u.id', $follower['id'], null));
    $followersOfUser[$i]['amount_of_followers'] = count(get_followers($con, $follower['id']));
    foreach ($followingUsersOfSessionUser as $followingUser) {
        if ($followingUser['id'] === $follower['id']) {
            $followersOfUser[$i]['subscribed_by_session_user'] = true;
            break;
        } else {
            $followersOfUser[$i]['subscribed_by_session_user'] = false;
        }
    }
}

$liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
if (isset($_GET['liked_post_id'])) {
    toggle_like($con, $_SESSION['user']['id'], $_GET['liked_post_id']);
    header("Location: /profile.php?id=" . $user['id']);
    exit();
}

$reposted_post_ids_by_session_user = get_all_reposted_post_ids_by_user($con, $_SESSION['user']['id']);
if (isset($_GET['reposted_post_id'])) {
    repost($con, $_GET['reposted_post_id']);
    header("Location: /profile.php?id=" . $user['id']);
    exit();
}

if (isset($_GET['subscribed'])) {
    add_follower($con, $_SESSION['user']['id'], $user['id']);
    header("Location: /profile.php?id=" . $user['id']);
    exit();
}
if (isset($_GET['unsubscribed'])) {
    remove_follower($con, $_SESSION['user']['id'], $user['id']);
    header("Location: /profile.php?id=" . $user['id']);
    exit();
}

$subscribe = false;
foreach ($followersOfUser as $follower) {
    if ($follower['id'] === intval($_SESSION['user']['id'])) {
        $subscribe = true;
    }
}

/*echo '<pre>';
print_r($userPosts);
echo '</pre>';
echo '<pre>';
print_r(repost($con, $userPosts[0]['id']));
echo '</pre>';*/

$main_content = include_template('profile.php', [
    'userPosts' => $userPosts,
    'tabs' => $tabs,
    'user' => $user,
    'selfPage' => $selfPage,
    'currentTab' => $currentTab,
    'amountOfUserPosts' => $amountOfUserPosts,
    'amountOfUserFollowers' => $amountOfUserFollowers,
    'likedPostsOfUser' => $likedPostsOfUser,
    'followingUsersOfUser' => $followingUsersOfUser,
    'followersOfUser' => $followersOfUser,
    'subscribe' => $subscribe,
    'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
    'reposted_post_ids_by_session_user' => $reposted_post_ids_by_session_user,
    'con' => $con,
]);

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

?>
<?= $layout; ?>
