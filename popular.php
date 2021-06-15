<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: популярное';
$types = get_post_types($con);
$sortTypes = [
    'popular' => [
        'db_property' => 'views_count',
        'title' => 'Популярность'
    ],
    'likes' => [
        'db_property' => 'likes_count',
        'title' => 'Лайки'
    ],
    'date' => [
        'db_property' => 'dt_add',
        'title' => 'Дата'
    ]
];

$pageNumber = 1;
if (isset($_GET["page_num"]) && $pageNumber > 1) {
    $pageNumber = $_GET["page_num"];
}

$limit = NUMBER_OF_PAGE_POSTS;
$offset = ($pageNumber - 1) * $limit;

if (isset($_GET["sort"])) {
    if (isset($_GET["type_id"])) {
        $typeId = $_GET["type_id"];
        $amountOfPosts = count(get_filtered_posts($con, 't.id', intval($typeId), null));
        $posts = get_filtered_posts($con, 't.id', intval($typeId), $offset . ',' . $limit,
            $sortTypes[$_GET["sort"]]["db_property"]);
    } else {
        $typeId = '';
        $amountOfPosts = count(get_filtered_posts($con, '', null, null));
        $posts = get_filtered_posts($con, '', null, $offset . ',' . $limit, $sortTypes[$_GET["sort"]]["db_property"]);
    }
} else {
    if (isset($_GET["type_id"])) {
        $typeId = $_GET["type_id"];
        $amountOfPosts = count(get_filtered_posts($con, 't.id', intval($typeId), null));
        $posts = get_filtered_posts($con, 't.id', intval($typeId), $offset . ',' . $limit);
    } else {
        $typeId = '';
        $amountOfPosts = count(get_filtered_posts($con, '', null, null));
        $posts = get_filtered_posts($con, '', null, $offset . ',' . $limit);
    }
}

$maxPage = ceil($amountOfPosts / NUMBER_OF_PAGE_POSTS);

$liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
if (isset($_GET['liked_post_id'])) {
    toggle_like($con, $_SESSION['user']['id'], $_GET['liked_post_id']);
    if (isset($_SERVER['HTTP_REFERER'])) {
        $url = $_SERVER['HTTP_REFERER'];
    } else {
        $url = '/popular.php';
    }
    header("Location: " . $url);
    exit();
}

if (isset($_GET["page_num"]) && (intval($_GET["page_num"]) < 1 || intval($_GET["page_num"]) > $maxPage)) {
    header('HTTP/1.0 404 not found');
    $main_content = include_template('404.php');
} else {
    $main_content = include_template('main.php', [
        'types' => $types,
        'sortTypes' => $sortTypes,
        'posts' => $posts,
        'typeId' => $typeId,
        'pageNumber' => $pageNumber,
        'maxPage' => $maxPage,
        'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
        'con' => $con,
    ]);
}

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

echo $layout;
