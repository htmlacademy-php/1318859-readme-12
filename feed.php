<?php

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: моя лента';
$current_tab = (isset($_GET["type"])) ? $_GET["type"] : '';

$tabs = [
    'photo' => 'фото',
    'video' => 'видео',
    'text' => 'текст',
    'quote' => 'цитата',
    'link' => 'ссылка',
];
print_r($current_tab);
$user_id = $_SESSION['user']['id'];
$user_posts = get_posts_of_user($con, $user_id);

if (empty($current_tab)) {
    $user_current_tab_posts = $user_posts;
    echo '<pre>';
    print_r($user_current_tab_posts);
    echo '</pre>';
} else {
    $user_current_tab_posts = [];
    foreach ($user_posts as $post) {
        echo '<pre>';
        print_r($post['class_name']);
        echo '</pre>';
        if ($current_tab === $post['class_name']) {
            $user_current_tab_posts += [$post];
            echo '<pre>';
            print_r($user_current_tab_posts);
            echo '</pre>';
        }
    }
}

$main_content = include_template('my-feed.php', [
    'user_current_tab_posts' => $user_current_tab_posts,
    'tabs' => $tabs,
    'user_id' => $user_id,
    'current_tab' => $current_tab,
]);

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

?>
<!--<pre>--><? // print_r($posts); ?><!--</pre>-->
<?= $layout; ?>
