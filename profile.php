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

$amountOfUserPosts = count(get_filtered_posts($con, 'u.id', $user['id']));
$amountOfUserFollowers = count(get_followers($con, $user['id']));
$userPosts = get_posts_of_user($con, $user['id']);

$likedPostsOfUser = get_liked_posts_of_user($con, $user['id']);
$followingUsersOfUser = get_following_users_of_user($con, $user['id']); //те, на кого подписан пользователь
$followersOfUser = get_followers($con, $user['id']); // подписчики пользователя

$liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
if (isset($_GET['liked_post_id'])) {
    toggle_like($con, $_SESSION['user']['id'], $_GET['liked_post_id']);
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
    if ($follower['follower_id'] === intval($_SESSION['user']['id'])) {
        $subscribe = true;
    }
}

echo '<pre>';
print_r($likedPostsOfUser);
echo '</pre>';

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
    'subscribe' => $subscribe,
    'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
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
