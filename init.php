<?php
session_start();

date_default_timezone_set('Europe/Moscow');

$configs = [
    'con' => mysqli_connect("localhost", "root", "", "readme"),
    'current_tab' => (isset($_GET["type"])) ? $_GET["type"] : 'text',
    'min_comment_length' => 4,
    'nav_links' => [
        'popular' => [
            'class_name' => 'popular',
            'href' => 'popular.php',
            'title' => 'Популярный контент'
        ],
        'feed' => [
            'class_name' => 'feed',
            'href' => 'feed.php',
            'title' => 'Моя лента'
        ],
        'messages' => [
            'class_name' => 'messages',
            'href' => '#',
            'title' => 'Личные сообщения'
        ],
    ]
];



$con = mysqli_connect("localhost", "root", "", "readme");
mysqli_set_charset($con, "utf8");
