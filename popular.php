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
$sort_types = [
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

$page_number = 1;
if (isset($_GET["page_num"]) && $page_number > 1) {
    $page_number = intval($_GET["page_num"]);
}

$limit = NUMBER_OF_PAGE_POSTS;
$offset = ($page_number - 1) * $limit;

if (isset($_GET["sort"])) {
    if (isset($_GET["type_id"])) {
        $type_id = intval($_GET["type_id"]);
        $amount_of_posts = count(get_filtered_posts($con, 't.id', $type_id, null));
        $posts = get_filtered_posts($con, 't.id', $type_id, $offset . ',' . $limit,
            $sort_types[$_GET["sort"]]["db_property"]);
    } else {
        $type_id = '';
        $amount_of_posts = count(get_filtered_posts($con, '', null, null));
        $posts = get_filtered_posts($con, '', null, $offset . ',' . $limit, $sort_types[$_GET["sort"]]["db_property"]);
    }
} else {
    if (isset($_GET["type_id"])) {
        $type_id = intval($_GET["type_id"]);
        $amount_of_posts = count(get_filtered_posts($con, 't.id', $type_id, null));
        $posts = get_filtered_posts($con, 't.id', $type_id, $offset . ',' . $limit);
    } else {
        $type_id = '';
        $amount_of_posts = count(get_filtered_posts($con, '', null, null));
        $posts = get_filtered_posts($con, '', null, $offset . ',' . $limit);
    }
}

$max_page = ceil($amount_of_posts / NUMBER_OF_PAGE_POSTS);

$liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
if (isset($_GET['liked_post_id'])) {
    toggle_like($con, intval($_SESSION['user']['id']), intval($_GET['liked_post_id']));
    if (isset($_SERVER['HTTP_REFERER'])) {
        $url = $_SERVER['HTTP_REFERER'];
    } else {
        $url = '/popular.php';
    }
    header("Location: " . $url);
    exit();
}

if (isset($_GET["page_num"]) && (intval($_GET["page_num"]) < 1 || intval($_GET["page_num"]) > $max_page)) {
    header('HTTP/1.0 404 not found');
    $main_content = include_template('404.php');
} else {
    $main_content = include_template('main.php', [
        'types' => $types,
        'sort_types' => $sort_types,
        'posts' => $posts,
        'type_id' => $type_id,
        'page_number' => $page_number,
        'max_page' => $max_page,
        'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
        'con' => $con,
    ]);
}

$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'user_id' => $_SESSION['user']['id'],
    'title' => $title,
    'nav_links' => $configs['nav_links'],
]);

echo $layout;
