<?php
include_once 'config.php';
include_once 'helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Миша';
$title = 'readme: популярное';
$posts = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'username' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'post_date' => generate_random_date(0),
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'username' => 'Владик',
        'avatar' => 'userpic.jpg',
        'post_date' => generate_random_date(1),
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'username' => 'Виктор',
        'avatar' => 'userpic-mark.jpg',
        'post_date' => generate_random_date(2),
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'username' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'post_date' => generate_random_date(3),
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'username' => 'Владик',
        'avatar' => 'userpic.jpg',
        'post_date' => generate_random_date(4),
    ],
];

$now = date_create('now');

$main_content = include_template('main.php', ['posts' => $posts,
    'now' => $now
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => $title,
    'is_auth' => $is_auth
]);
?>

<?= $layout; ?>
