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

if (isset($_GET["sort"])) {
    if (isset($_GET["type_id"])) {
        $typeId = $_GET["type_id"];
        $posts = get_filtered_posts($con, 't.id', intval($typeId), NUMBER_OF_PAGE_POSTS, $sortTypes[$_GET["sort"]]["db_property"]);
    } else {
        $typeId = '';
        $posts = get_filtered_posts($con, '', null, NUMBER_OF_PAGE_POSTS, $sortTypes[$_GET["sort"]]["db_property"]);
    }
} else {
    if (isset($_GET["type_id"])) {
        $typeId = $_GET["type_id"];
        $posts = get_filtered_posts($con, 't.id', intval($typeId), NUMBER_OF_PAGE_POSTS);
    } else {
        $typeId = '';
        $posts = get_filtered_posts($con, '', null, NUMBER_OF_PAGE_POSTS);
    }
}

$liked_post_ids_by_session_user = get_all_liked_post_ids_by_user($con, $_SESSION['user']['id']);
if (isset($_GET['liked_post_id'])) {
    toggle_like($con, $_SESSION['user']['id'], $_GET['liked_post_id']);
    if(isset($_SERVER['HTTP_REFERER'])) {
        $url = $_SERVER['HTTP_REFERER'];
    } else {
        $url = '/popular.php';
    }
    header("Location: " . $url);
    exit();
}

/*echo '<pre>';
print_r($posts);
echo '</pre>';*/

$main_content = include_template('main.php', [
    'types' => $types,
    'sortTypes' => $sortTypes,
    'posts' => $posts,
    'typeId' => $typeId,
    'liked_post_ids_by_session_user' => $liked_post_ids_by_session_user,
    'con' => $con,
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $_SESSION['user']['login'],
    'user_avatar' => $_SESSION['user']['avatar'],
    'title' => $title,
]);

?>
<?= $layout; ?>
