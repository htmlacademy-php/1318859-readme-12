<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: моя лента';
$currentTab = (isset($_GET["type"])) ? $_GET["type"] : '';

$tabs = [
    'photo' => 'фото',
    'video' => 'видео',
    'text' => 'текст',
    'quote' => 'цитата',
    'link' => 'ссылка',
];
$user_id = $_SESSION['user']['id'];
$userPosts = get_posts_of_user($con, $user_id);

if (empty($currentTab)) {
    $user_current_tab_posts = $userPosts;
} else {
    $user_current_tab_posts = [];
    $i = 0;
    foreach ($userPosts as $post) {
        if ($currentTab === $post['class_name']) {
            $user_current_tab_posts[$i] = $post;
            $i++;
        }
    }
}

echo '<pre>';
print_r($userPosts);
echo '</pre>';

$main_content = include_template('my-feed.php', [
    'user_current_tab_posts' => $user_current_tab_posts,
    'tabs' => $tabs,
    'user_id' => $user_id,
    'currentTab' => $currentTab,
]);

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

?>
<?= $layout; ?>
