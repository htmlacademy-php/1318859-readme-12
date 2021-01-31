<?php
include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: добавление публикации';
$types = get_post_types($con);
$id = '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: /add.php?success=true");
}

$main_content = include_template('adding-post.php', ['types' => $types, 'id' => $id,]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'user_name' => $user_name, 'title' => $title, 'is_auth' => $is_auth]);
?>

<?= $layout; ?>


