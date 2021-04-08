<?php

function get_post_types($con) {
    $sql = "SELECT * FROM types";
    $stmt = mysqli_prepare($con, $sql);
    $types = get_data($con, $stmt, false);
    return $types;
}

function get_all_posts($con) {
    $sql = "SELECT * FROM posts;";
    $stmt = mysqli_prepare($con, $sql);
    $posts = get_data($con, $stmt, false);
    return $posts;
}

function get_all_tags($con) {
    $sql = "SELECT * FROM tags;";
    $stmt = mysqli_prepare($con, $sql);
    $tags = get_data($con, $stmt, false);
    return $tags;
}

function get_filtered_posts($con, $filtered_property, $value) {
    $limit = NUMBER_OF_PAGE_POSTS;
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id $sql_filter ORDER BY views_count DESC LIMIT ?;";
//    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id ? ORDER BY views_count DESC LIMIT ?;";


//    $stmt = db_get_prepare_stmt($con, $sql, [$sql_filter, $limit]);
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $limit);
    $filtered_posts = get_data($con, $stmt, false);
    return $filtered_posts;
}

function get_post($con, $filtered_property, $value) {
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
//    var_dump($sql_filter);
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id $sql_filter;";
    $stmt = mysqli_prepare($con, $sql);
//    mysqli_stmt_bind_param($stmt, 's',$sql_filter);
    $post = get_data($con, $stmt, true);
    return $post;
}

function get_user($con, $user_id) {
    $sql = "SELECT * FROM users WHERE id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $user = get_data($con, $stmt, true);
    return $user;
}

function get_followers($con, $following_user_id) {
    $sql = "SELECT * FROM follows WHERE following_user_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $following_user_id);
    $followers = get_data($con, $stmt, false);
    return $followers;
}

function add_post($con, $data) {
    $sql_data = '';
    foreach ($data as $db_col_name => $value) {
        $sql_data .= " $db_col_name = '$value',";
    }
    $sql = "INSERT INTO posts SET" . substr($sql_data, 0, -1) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    } else {
        $last_id = mysqli_insert_id($con);
    }
    return $last_id;
}

function add_user($con, $data) {
    $sql_data = '';
    foreach ($data as $db_col_name => $value) {
        $sql_data .= " $db_col_name = '$value',";
    }
    $sql = "INSERT INTO users SET" . substr($sql_data, 0, -1) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    } else {
        $user_id = mysqli_insert_id($con);
    }
    return $user_id;
}

function add_tags($con, $postTags, $db_tags, $post_id) {
    $tag_names = [];
    $key = 0;
    foreach ($db_tags as $db_tag) {
        $tag_names += [$key => $db_tag['name']];
        $key += 1;
    }
    foreach ($postTags as $tag) {
        if (!in_array($tag, $tag_names)) {
            $sql = "INSERT INTO tags SET name = '$tag';";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                $error = mysqli_error($con);
                print("Ошибка MySQL: " . $error);
            }
            $sql = "INSERT INTO posts_tags SET post_id = '$post_id', tag_name = '$tag';";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                $error = mysqli_error($con);
                print("Ошибка MySQL: " . $error);
            }
        }
    }
}

function get_search_posts($con, $search) {
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id WHERE MATCH(p.title, p.text_content) AGAINST(?) ORDER BY views_count DESC";

    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    $posts = get_data($con, $stmt, false);
    return $posts;
}

function get_post_tags($con, $post_id) {
    $sql = "SELECT tag_name FROM posts_tags WHERE post_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    $post_tags = get_data($con, $stmt, false);
    return $post_tags;
}

function get_posts_with_tag($con, $tag_name) {
    $sql = "SELECT post_id FROM posts_tags WHERE tag_name = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $tag_name);
    $post_tags = get_data($con, $stmt, false);
    return $post_tags;
}

function find_posts_with_tag($con, $post_id) {
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id WHERE p.id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    $filtered_posts = get_data($con, $stmt, false);
    return $filtered_posts;
}

function get_posts_of_user($con, $user_id) {
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id WHERE u.id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $filtered_posts = get_data($con, $stmt, false);
    return $filtered_posts;
}

// определяем id постов пользователя
function get_ids_of_user_posts($con, $user_id) {
    $sql = "SELECT id FROM posts WHERE user_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $ids = get_data($con, $stmt, false);
    for ($i = 0; $i < count($ids); $i++) {
        $ids[$i] = $ids[$i]['id'];
    }
    return $ids;
}

function get_liked_posts_of_user($con, $user_id) {
    $ids = implode(",", get_ids_of_user_posts($con, $user_id));
    // выбираем из таблицы лайков лайки постов пользователя, считаем количество лайков(строк в таблице) каждого поста
    // группируем по id поста, сортируем по дате последнего лайка.
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name, MAX(l.id) AS last_like_id, COUNT(l.post_id) AS post_likes FROM likes l 
            JOIN posts p ON p.id = l.post_id
            JOIN users u ON p.user_id = u.id
            JOIN types t ON p.type_id = t.id
            WHERE l.post_id IN (" . $ids . ") GROUP BY l.post_id ORDER BY last_like_id DESC;";
    $stmt = mysqli_prepare($con, $sql);
    $liked_user_posts = get_data($con, $stmt, false);
    return $liked_user_posts;
}

function get_following_users_of_user($con, $user_id) {
    // выбираем из таблицы подписок ысе строки с follower_id == user_id.
    $sql = "SELECT u.* FROM users u JOIN follows f ON u.id = f.following_user_id WHERE f.follower_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $following_users_of_user = get_data($con, $stmt, false);
    return $following_users_of_user;
}
