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

function get_filtered_posts($con, $filtered_property, $value, $limit, $order = 'views_count', $direction = 'DESC') {
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    if ($limit) {
        $sql_limit = " LIMIT $limit";
    } else {
        $sql_limit = "";
    }
    if ($order) {
        $sql_order = " ORDER BY $order $direction";
    } else {
        $sql_order = "";
    }
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name, (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id)  AS likes_count FROM posts p 
            JOIN users u ON p.user_id = u.id 
            JOIN types t ON p.type_id = t.id 
            $sql_filter $sql_order $sql_limit;";
    $stmt = mysqli_prepare($con, $sql);
    $filtered_posts = get_data($con, $stmt, false);
    return $filtered_posts;
}

function get_post($con, $filtered_property, $value) {
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id $sql_filter;";
    $stmt = mysqli_prepare($con, $sql);
    $post = get_data($con, $stmt, true);
    return $post;
}

function get_post_for_repost($con, $filtered_property, $value) {
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql = "SELECT * FROM posts $sql_filter;";
    $stmt = mysqli_prepare($con, $sql);
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
    $sql = "SELECT u.* FROM follows f 
            JOIN users u ON u.id = f.follower_id 
            WHERE following_user_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $following_user_id);
    $followers = get_data($con, $stmt, false);
    return $followers;
}

function add_follower($con, $follower_user, $following_user) {
    $followers = get_followers($con, $following_user['id']);
    foreach ($followers as $follower) {
        if ($follower['id'] === intval($follower_user['id'])) {
            return false;
        }
    }
    $sql = "INSERT INTO follows SET follower_id = '" . $follower_user['id'] . "', following_user_id = '" . $following_user['id'] . "';";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
    sendSubscribeNotification($follower_user, $following_user);
}

function remove_follower($con, $follower_id, $following_user_id) {
    $sql = "DELETE FROM follows WHERE follower_id = '$follower_id' AND following_user_id = '$following_user_id';";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
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
        $sql = "SELECT * FROM posts WHERE id = LAST_INSERT_ID()";
        $stmt = mysqli_prepare($con, $sql);
        $post = get_data($con, $stmt, true);
        return $post;
    }
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

function add_comment($con, $data) {
    $sql_data = '';
    foreach ($data as $db_col_name => $value) {
        $sql_data .= " $db_col_name = '$value',";
    }
    $sql = "INSERT INTO comments SET" . substr($sql_data, 0, -1) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
}

function add_view($con, $post_id) {
    $sql = "UPDATE posts SET `views_count` = `views_count` + 1 WHERE `id` = $post_id;";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
}

function add_tags($con, $postTags, $db_tags, $post_id) {
    $post_tags_ids = [];
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
            $tag_id = mysqli_insert_id($con);
            $post_tags_ids[] = $tag_id;
        } else {
            $post_tags_ids[] = $tag['id'];
        }
    }
    foreach ($post_tags_ids as $id) {
        $sql = "INSERT INTO posts_tags SET post_id = '$post_id', tag_id = '$id';";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
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
    $sql = "SELECT name FROM tags t
            JOIN posts_tags pt ON t.id = pt.tag_id
            WHERE pt.post_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    $post_tags = get_data($con, $stmt, false);
    return $post_tags;
}

function get_post_comments($con, $post_id) {
    $sql = "SELECT u.*, c.content, c.dt_add AS publish_time FROM comments c
            JOIN users u ON u.id = c.user_id
            WHERE c.post_id = ?
            ORDER BY publish_time DESC;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    $post_tags = get_data($con, $stmt, false);
    return $post_tags;
}

function get_posts_with_tag($con, $tag_name) {
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p 
            JOIN users u ON p.user_id = u.id 
            JOIN types t ON p.type_id = t.id 
            JOIN posts_tags pt ON pt.post_id = p.id
            JOIN tags tg ON tg.id = pt.tag_id
            WHERE tg.name = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $tag_name);
    $post_tags = get_data($con, $stmt, false);
    return $post_tags;
}

function get_posts_of_user($con, $user_id) {
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id WHERE u.id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $filtered_posts = get_data($con, $stmt, false);
    return $filtered_posts;
}

/*function get_posts_of_users_following_by_user($con, $user_id) {
    $sql = "SELECT p.*, u.login, u.avatar FROM posts p
            JOIN follows f ON p.user_id = f.following_user_id 
            JOIN users u ON u.id = f.follower_id
            WHERE u.id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $filtered_posts = get_data($con, $stmt, false);
    return $filtered_posts;
}*/

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
    $sql = "SELECT p.*, u.login, u.avatar, l.user_id AS like_user_id, l.dt_add AS like_dt_add, t.class_name FROM posts p
            JOIN likes l ON l.post_id = p.id
            JOIN users u ON u.id = l.user_id
            JOIN types t ON t.id = p.type_id
            WHERE p.user_id = '$user_id' AND (SELECT COUNT(*) FROM likes WHERE post_id = p.id ) > 0
            ORDER BY l.dt_add DESC;";
    $stmt = mysqli_prepare($con, $sql);
    $liked_user_posts = get_data($con, $stmt, false);
    return $liked_user_posts;
}

function get_following_users_of_user($con, $user_id) {
    // выбираем из таблицы подписок все строки с follower_id == user_id.
    $sql = "SELECT u.* FROM users u JOIN follows f ON u.id = f.following_user_id WHERE f.follower_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $followingUsersOfUser = get_data($con, $stmt, false);
    return $followingUsersOfUser;
}

function toggle_like($con, $userId, $postId) {
    $sql = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $postId);
    $like = get_data($con, $stmt, true);

    if ($like) {
        $sql = "DELETE FROM likes WHERE user_id = '$userId' AND post_id = '$postId';";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        }
    } else {
        $sql = "INSERT INTO likes SET user_id = '$userId', post_id = '$postId';";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        }
    }
}

function get_all_liked_post_ids_by_user($con, $user_id) {
    $sql = "SELECT post_id FROM likes WHERE user_id = '$user_id';";
    $stmt = mysqli_prepare($con, $sql);
    $all_liked_post_ids_by_user_from_db = get_data($con, $stmt, false);
    $all_liked_post_ids_by_user = [];
    $i = 0;
    foreach ($all_liked_post_ids_by_user_from_db as $item) {
        $all_liked_post_ids_by_user += [$i => $item['post_id']];
        $i++;
    }
    return $all_liked_post_ids_by_user;
}

function get_all_reposted_post_ids_by_user($con, $user_id) {
    $sql = "SELECT repost_id FROM posts WHERE user_id = '$user_id' AND repost_id IS NOT NULL;";
    $stmt = mysqli_prepare($con, $sql);
    $all_reposted_post_ids_by_user_from_db = get_data($con, $stmt, false);
    $all_reposted_post_ids_by_user = [];
    $i = 0;
    foreach ($all_reposted_post_ids_by_user_from_db as $item) {
        $all_reposted_post_ids_by_user += [$i => $item['repost_id']];
        $i++;
    }
    return $all_reposted_post_ids_by_user;
}

function count_likes_of_post($con, $post_id) {
    $sql = "SELECT id FROM likes WHERE post_id = '$post_id';";
    $result = mysqli_query($con, $sql);
    $records_count = mysqli_num_rows($result);
    return $records_count;
}

function count_comments_of_post($con, $post_id) {
    $sql = "SELECT id FROM comments WHERE post_id = '$post_id';";
    $result = mysqli_query($con, $sql);
    $records_count = mysqli_num_rows($result);
    return $records_count;
}

function count_reposts_of_post($con, $post_id) {
    $sql = "SELECT id FROM posts WHERE repost_id = '$post_id';";
    $result = mysqli_query($con, $sql);
    $records_count = mysqli_num_rows($result);
    return $records_count;
}

function repost($con, $post_id) {
    $post = get_post_for_repost($con, 'id', $post_id);
    $sql = "SELECT * FROM posts WHERE user_id = ? AND repost_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', intval($_SESSION['user']['id']), $post_id);
    $isReposted = get_data($con, $stmt, true);
    if ($post && !$isReposted) {
        $post['author_id'] = $post['user_id'];
        $post['user_id'] = $_SESSION['user']['id'];
        $post['dt_add'] = date("Y-m-d H:i:s");
        $post['repost_id'] = $post['id'];
        $post['views_count'] = intval($post['views_count']);
        unset($post['id']);
        add_post($con, $post);
        header("Location: /profile.php?id=" . $post['user_id']);
    }
    return false;
}
