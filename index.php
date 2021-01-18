<?php
include_once 'config.php';
include_once 'helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Миша';
$title = 'readme: популярное';

$now = date_create('now');

$con = mysqli_connect("localhost", "root", "","readme");
if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}
else {
    mysqli_set_charset($con, "utf8");
    $sql_types = "SELECT * FROM types";
    $result_types = mysqli_query($con, $sql_types);
    $rows_types = mysqli_fetch_all($result_types, MYSQLI_ASSOC);

    $number_of_posts = 6;
    $sql_posts = "SELECT p.*, u.login, u.avatar, t.icon_class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id  ORDER BY views_count DESC LIMIT $number_of_posts;";
    $result_posts = mysqli_query($con, $sql_posts);
    $rows_posts = mysqli_fetch_all($result_posts, MYSQLI_ASSOC);

    mysqli_close($con);
}


$main_content = include_template('main.php', [
    'now' => $now,
    'rows_types' => $rows_types,
    'rows_posts' => $rows_posts
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => $title,
    'is_auth' => $is_auth
]);
?>

<?= $layout; ?>
