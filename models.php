<?php

function get_post_types($con)
{
    if ($con === false) {
        return print("Ошибка подключения: " . mysqli_connect_error());
    }

    $sql_types = "SELECT * FROM types";
    $result_types = mysqli_query($con, $sql_types);
    $types = mysqli_fetch_all($result_types, MYSQLI_ASSOC);

    return $types;
}

;

function get_posts($con, $filtered_property, $value)
{
    if ($con === false) {
        return print("Ошибка подключения: " . mysqli_connect_error());
    }
    $limit = NUMBER_OF_POSTS;
    if ($filtered_property) {
        $sql_filter = " WHERE t.$filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql_posts = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id $sql_filter ORDER BY views_count DESC LIMIT $limit;";

    $result_posts = mysqli_query($con, $sql_posts);
    $posts = mysqli_fetch_all($result_posts, MYSQLI_ASSOC);

    return $posts;
}

;
