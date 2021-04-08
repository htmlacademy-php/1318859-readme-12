<?php

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: профиль';
$current_tab = (isset($_GET["tab"])) ? $_GET["tab"] : 'posts';

$tabs = [
    'posts' => 'посты',
    'likes' => 'лайки',
    'follows' => 'подписки',
];
print_r($current_tab);
$user = get_user($con, $_GET['id']);
$amount_of_user_posts = count(get_filtered_posts($con, 'u.id', $user['id']));
$number_of_user_followers = count(get_followers($con, $user['id']));
$user_posts = get_posts_of_user($con, $user['id']);

$liked_posts_of_user = get_liked_posts_of_user($con, $user['id']);
$following_users_of_user = get_following_users_of_user($con, $user['id']);

echo '<pre>';
print_r($user);
//print_r($following_users_of_user);
echo '</pre>';

$main_content = include_template('profile.php', [
    'user_posts' => $user_posts,
    'tabs' => $tabs,
    'user' => $user,
    'current_tab' => $current_tab,
    'amount_of_user_posts' => $amount_of_user_posts,
    'number_of_user_followers' => $number_of_user_followers,
    'liked_posts_of_user' => $liked_posts_of_user,
    'following_users_of_user' => $following_users_of_user,
]);

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

?>
<?= $layout; ?>
