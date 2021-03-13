<?php

if (isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: моя лента';

$main_content = include_template('my-feed.php', []);

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

?>
<!--<pre>--><?// print_r($posts); ?><!--</pre>-->
<?= $layout; ?>
