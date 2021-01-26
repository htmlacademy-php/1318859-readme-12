<?php
define("NUMBER_OF_POSTS", 6);

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';


$is_auth = rand(0, 1);
$user_name = 'Миша';
$title = 'readme: популярное';

/*if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}
else {
    $sql_types = "SELECT * FROM types";
    $result_types = mysqli_query($con, $sql_types);
    $types = mysqli_fetch_all($result_types, MYSQLI_ASSOC);

    $limit = NUMBER_OF_POSTS;
    $sql_posts = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id  ORDER BY views_count DESC LIMIT $limit;";
    $result_posts = mysqli_query($con, $sql_posts);
    $posts = mysqli_fetch_all($result_posts, MYSQLI_ASSOC);

    mysqli_close($con);
}*/
$types = get_post_types($con);

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $posts = get_posts($con, 'id', $id);
} else {
    $id = '';
    $posts = get_posts($con, '', null);
}

$main_content = include_template('main.php', [
    'types' => $types,
    'posts' => $posts,
    'id' => $id
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => $title,
    'is_auth' => $is_auth
]);
?>

<?= $layout; ?>
