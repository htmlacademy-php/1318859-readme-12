<?php

function get_post_types($con) {
    $sql_types = "SELECT * FROM types";
    $types = get_data($con, $sql_types, false);
    return $types;
}

function get_all_posts($con) {
    $sql_posts = "SELECT * FROM posts;";
    $posts = get_data($con, $sql_posts, false);
    return $posts;
}

function get_filtered_posts($con, $filtered_property, $value) {
    $limit = NUMBER_OF_PAGE_POSTS;
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql_filtered_posts = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id $sql_filter ORDER BY views_count DESC LIMIT $limit;";
    $filtered_posts = get_data($con, $sql_filtered_posts, false);
    return $filtered_posts;
}

function get_post($con, $filtered_property, $value) {
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql_post = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id $sql_filter;";
    $post = get_data($con, $sql_post, true);
    return $post;
}

function get_user($con, $user_id) {
    $sql_user = "SELECT * FROM users WHERE id = $user_id;";
    $user = get_data($con, $sql_user, true);
    return $user;
}

function get_followers($con, $following_user_id) {
    $sql_followers = "SELECT * FROM follows WHERE following_user_id = $following_user_id;";
    $followers = get_data($con, $sql_followers, false);
    return $followers;
}
