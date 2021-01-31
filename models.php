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

function get_filtered_posts($con, $filtered_property, $value) {
    $limit = NUMBER_OF_PAGE_POSTS;
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id $sql_filter ORDER BY views_count DESC LIMIT ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i',$limit);
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
    mysqli_stmt_bind_param($stmt, 'i',$user_id);
    $user = get_data($con, $stmt, true);
    return $user;
}

function get_followers($con, $following_user_id) {
    $sql = "SELECT * FROM follows WHERE following_user_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i',$following_user_id);
    $followers = get_data($con, $stmt, false);
    return $followers;
}
