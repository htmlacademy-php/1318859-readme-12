<?php
session_start();

date_default_timezone_set('Europe/Moscow');

$con = mysqli_connect("localhost", "root", "", "readme");
if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}

mysqli_set_charset($con, "utf8");

$configs = [
    'con'                => $con,
    'current_tab'        => (isset($_GET["type"])) ? $_GET["type"] : 'text',
    'min_comment_length' => 4,
    'nav_links'          => [
        'popular'  => [
            'class_name' => 'popular',
            'href'       => 'popular.php',
            'title'      => 'Популярный контент',
        ],
        'feed'     => [
            'class_name' => 'feed',
            'href'       => 'feed.php',
            'title'      => 'Моя лента',
        ],
        'messages' => [
            'class_name' => 'messages',
            'href'       => '#',
            'title'      => 'Личные сообщения',
        ],
    ],
];
