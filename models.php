<?php

/**
 * Возвращает массив данных о типах поста.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 *
 * @return array
 */
function get_post_types($con)
{
    $sql = "SELECT * FROM `types`";
    $stmt = mysqli_prepare($con, $sql);
    $types = get_data($con, $stmt, false);
    if (!isset($types)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $types;
}

/**
 * Возвращает массив данных о всех постах.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 *
 * @return array
 */
function get_all_posts($con)
{
    $sql = "SELECT * FROM `posts`;";
    $stmt = mysqli_prepare($con, $sql);
    $posts = get_data($con, $stmt, false);
    if (!isset($posts)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $posts;
}

/**
 * Возвращает массив данных о всех пользователях.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 *
 * @return array
 */
function get_all_users($con)
{
    $sql = "SELECT * FROM `users`;";
    $stmt = mysqli_prepare($con, $sql);
    $users = get_data($con, $stmt, false);
    if (!isset($users)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $users;
}

/**
 * Возвращает массив данных о всех тегах.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 *
 * @return array
 */
function get_all_tags($con)
{
    $sql = "SELECT * FROM `tags`;";
    $stmt = mysqli_prepare($con, $sql);
    $tags = get_data($con, $stmt, false);
    if (!isset($tags)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $tags;
}

/**
 * Возвращает массив данных о постах по фильтру.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                                  объект с данными, иначе - false.
 * @param string $filtered_property - название колонки из таблицы базы данных, по которой происходит фильтрация.
 * @param string $value             - значение свойства, по которому происходит фильтрация.
 * @param int    $limit             - ограничение количества выводимых постов.
 * @param string $order             - название колонки из таблицы базы данных, по которой происходит сортировка.
 * @param string $direction         - направление сортировки.
 *
 * @return array
 */
function get_filtered_posts($con, $filtered_property, $value, $limit, $order = 'views_count', $direction = 'DESC')
{
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
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name, 
       (SELECT COUNT(*) FROM `likes` l WHERE l.post_id = p.id) AS likes_count FROM `posts` p 
            JOIN `users` u ON p.user_id = u.id 
            JOIN `types` t ON p.type_id = t.id 
            $sql_filter $sql_order $sql_limit;";
    $stmt = mysqli_prepare($con, $sql);
    $filtered_posts = get_data($con, $stmt, false);
    if (!isset($filtered_posts)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $filtered_posts;
}

/**
 * Возвращает массив данных о посте по фильтру.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                                  объект с данными, иначе - false.
 * @param string $filtered_property - название колонки из таблицы базы данных, по которой происходит фильтрация.
 * @param string $value             - значение свойства, по которому происходит фильтрация.
 *
 * @return array
 */
function get_post($con, $filtered_property, $value)
{
    if ($filtered_property) {
        $sql_filter = " WHERE $filtered_property = $value";
    } else {
        $sql_filter = "";
    }
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM `posts` p 
            JOIN `users` u ON p.user_id = u.id 
            JOIN `types` t ON p.type_id = t.id $sql_filter;";
    $stmt = mysqli_prepare($con, $sql);
    $post = get_data($con, $stmt, true);
    if (!isset($post)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $post;
}

/**
 * Возвращает массив данных о посте, который репостится.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста в таблице `posts` базы данных.
 *
 * @return array
 */
function get_post_for_repost($con, $post_id)
{
    $sql = "SELECT * FROM `posts` WHERE `id` = $post_id;";
    $stmt = mysqli_prepare($con, $sql);
    $post = get_data($con, $stmt, true);
    if (!isset($post)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $post;
}

/**
 * Возвращает массив данных о пользователе.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя в таблице `users` базы данных.
 *
 * @return array
 */
function get_user($con, $user_id)
{
    $sql = "SELECT * FROM `users` WHERE `id` = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $user = get_data($con, $stmt, true);
    if (!isset($user)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $user;
}

/**
 * Возвращает массив данных о подписчиках пользователя.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                               объект с данными, иначе - false.
 * @param int $following_user_id - идентификатор пользователя в таблице `follows` базы данных, данные о чьих
 *                               подписчиках выводятся.
 *
 * @return array
 */
function get_followers($con, $following_user_id)
{
    $sql = "SELECT u.* FROM `follows` f 
            JOIN `users` u ON u.id = f.follower_id 
            WHERE `following_user_id` = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $following_user_id);
    $followers = get_data($con, $stmt, false);
    if (!isset($followers)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $followers;
}

/**
 * Добавляет в базу данных нового подписчика.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                              объект с данными, иначе - false.
 * @param array $follower_user  - массив данных о добавляемом подписчике.
 * @param array $following_user - массив данных о пользователе, чей подписчик добавляется.
 *
 * @return bool
 */
function add_follower($con, $follower_user, $following_user)
{
    $followers = get_followers($con, $following_user['id']);
    foreach ($followers as $follower) {
        if ($follower['id'] === intval($follower_user['id'])) {
            return false;
        }
    }
    $sql = "INSERT INTO `follows` SET `follower_id` = '" . intval($follower_user['id']) . "', `following_user_id` = '"
        . intval($following_user['id']) . "';";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    send_subscribe_notification($follower_user, $following_user);
}

/**
 * Удаляет из базы данных подписчика.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                               объект с данными, иначе - false.
 * @param int $follower_id       - идентификатор удаляемого подписчика в таблице `follows` базы данных.
 * @param int $following_user_id - идентификатор пользователя в таблице `follows` базы данных, от которого отписывается
 *                               удаляемый подписчик.
 */
function remove_follower($con, $follower_id, $following_user_id)
{
    $sql = "DELETE FROM `follows` WHERE `follower_id` = '" . intval($follower_id) . "' 
            AND `following_user_id` = '" . intval($following_user_id) . "';";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
}

/**
 * Добавляет в базу данных новый пост.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param array $data        - данные о добавляемом посте.
 *
 * @return array
 */
function add_post($con, $data)
{
    $sql_data = '';
    foreach ($data as $db_col_name => $value) {
        $sql_data .= " $db_col_name = '" . mysqli_real_escape_string($con, $value) . "',";
    }
//    $sql_data = mysqli_real_escape_string($con, $sql_data);
    $sql = "INSERT INTO `posts` SET" . substr($sql_data, 0, -1) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    } else {
        $sql = "SELECT * FROM `posts` WHERE `id` = LAST_INSERT_ID()";
        $stmt = mysqli_prepare($con, $sql);
        $post = get_data($con, $stmt, true);
        if (!isset($post)) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
            return false;
        }
        return $post;
    }
}

/**
 * Добавляет в базу данных нового пользователя.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param array $data        - данные о добавляемом пользователе.
 *
 * @return int
 */
function add_user($con, $data)
{
    $sql_data = '';
    foreach ($data as $db_col_name => $value) {
        $sql_data .= " $db_col_name = '" . mysqli_real_escape_string($con, $value) . "',";
    }
    $sql = "INSERT INTO `users` SET" . substr($sql_data, 0, -1) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $user_id = mysqli_insert_id($con);
    return $user_id;
}

/**
 * Добавляет в базу данных новый комментарий.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param array $data        - данные о добавляемом комментарии.
 */
function add_comment($con, $data)
{
    $sql_data = '';
    foreach ($data as $db_col_name => $value) {
        $sql_data .= " $db_col_name = '" . mysqli_real_escape_string($con, $value) . "',";
    }
    $sql = "INSERT INTO `comments` SET" . substr($sql_data, 0, -1) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
}

/**
 * Добавляет в базу данных новое сообщение.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param array $data        - данные о добавляемом сообщении.
 */
function add_message($con, $data)
{
    $sql_data = '';
    foreach ($data as $db_col_name => $value) {
        $sql_data .= " $db_col_name = '" . mysqli_real_escape_string($con, $value) . "',";
    }
    $sql = "INSERT INTO `messages` SET" . substr($sql_data, 0, -1) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
}

/**
 * Добавляет посту один просмотр (увеличивает значение в столбце `views_count` таблицы `posts` у поста с
 * идентификатором $post_id).
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста.
 */
function add_view($con, $post_id)
{
    $sql = "UPDATE `posts` SET `views_count` = `views_count` + 1 WHERE `id` = " . intval($post_id) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
}

/**
 * Добавляет информацию о тегах нового поста в базу данных.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param array $post_tags   - массив тегов добавляемого поста.
 * @param array $db_tags     - массив существующих в базе данных тегов.
 * @param int   $post_id     - идентификатор добавляемого поста.
 */
function add_tags($con, $post_tags, $db_tags, $post_id)
{
    $post_tags_ids = [];
    $tag_names = [];
    $key = 0;
    foreach ($db_tags as $db_tag) {
        $tag_names += [$key => $db_tag['name']];
        $key += 1;
    }
    foreach ($post_tags as $tag) {
        $tag = mysqli_real_escape_string($con, $tag);
        if (!in_array($tag, $tag_names)) {
            $sql = "INSERT INTO `tags` SET `name` = '$tag';";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                $error = mysqli_error($con);
                print("Ошибка MySQL: " . $error);
            } else {
                $tag_id = mysqli_insert_id($con);
                $post_tags_ids[] = $tag_id;
            }
        } else {
            foreach ($db_tags as $db_tag) {
                if ($db_tag['name'] === $tag) {
                    $post_tags_ids[] = $db_tag['id'];
                }
            }
        }
    }
    foreach ($post_tags_ids as $id) {
        $sql = "INSERT INTO `posts_tags` SET `post_id` = '" . intval($post_id) . "', `tag_id` = '" . intval($id) . "';";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        }
    }
}

/**
 * Возвращает массив данных о постах по запросу в поисковой строке.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param string $search     - поисковый запрос.
 *
 * @return array
 */
function get_search_posts($con, $search)
{
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM `posts` p 
            JOIN `users` u ON p.user_id = u.id 
            JOIN `types` t ON p.type_id = t.id 
            WHERE MATCH(p.title, p.text_content) AGAINST(?)";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $search);
    $posts = get_data($con, $stmt, false);
    if (!isset($posts)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $posts;
}

/**
 * Возвращает массив данных о тегах поста с идентификатором $post_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста.
 *
 * @return array
 */
function get_post_tags($con, $post_id)
{
    $sql = "SELECT name FROM `tags` t
            JOIN `posts_tags` pt ON t.id = pt.tag_id
            WHERE pt.post_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    $post_tags = get_data($con, $stmt, false);
    if (!isset($post_tags)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $post_tags;
}

/**
 * Возвращает массив данных о комментариях поста с идентификатором $post_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста.
 *
 * @return array
 */
function get_post_comments($con, $post_id)
{
    $sql = "SELECT u.*, c.content, c.dt_add AS publish_time FROM `comments` c
            JOIN `users` u ON u.id = c.user_id
            WHERE c.post_id = ?
            ORDER BY `publish_time` DESC;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    $post_comments = get_data($con, $stmt, false);
    if (!isset($post_comments)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $post_comments;
}

/**
 * Возвращает массив данных о постах с определённым тегом.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param string $tag_name   - тег, по наличию которого выбираются посты.
 *
 * @return array
 */
function get_posts_with_tag($con, $tag_name)
{
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM `posts` p 
            JOIN `users` u ON p.user_id = u.id 
            JOIN `types` t ON p.type_id = t.id 
            JOIN `posts_tags` pt ON pt.post_id = p.id
            JOIN `tags` tg ON tg.id = pt.tag_id
            WHERE tg.name = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $tag_name);
    $post_tags = get_data($con, $stmt, false);
    if (!isset($post_tags)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $post_tags;
}

/**
 * Возвращает массив данных о постах пользователя с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_posts_of_user($con, $user_id)
{
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM `posts` p 
            JOIN `users` u ON p.user_id = u.id 
            JOIN `types` t ON p.type_id = t.id 
            WHERE u.id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $filtered_posts = get_data($con, $stmt, false);
    if (!isset($filtered_posts)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $filtered_posts;
}

/**
 * Возвращает массив данных о постах пользователей, на которых подписан пользователь с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_posts_of_following_users($con, $user_id)
{
    $sql = "SELECT p.*, u.login, u.avatar, t.class_name FROM `posts` p 
            JOIN `follows` f ON p.user_id = f.following_user_id 
            JOIN `types` t ON p.type_id = t.id 
            JOIN `users` u ON f.following_user_id = u.id 
            WHERE f.follower_id = $user_id
            ORDER BY p.dt_add DESC;";
    $stmt = mysqli_prepare($con, $sql);
    $filtered_posts = get_data($con, $stmt, false);
    if (!isset($filtered_posts)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $filtered_posts;
}

/**
 * Возвращает массив данных о постах, которые лайкнул пользователь с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_liked_posts_of_user($con, $user_id)
{
    $sql = "SELECT p.*, u.login, u.avatar, l.user_id AS like_user_id, l.dt_add AS like_dt_add, t.class_name 
            FROM `posts` p
            JOIN `likes` l ON l.post_id = p.id
            JOIN `users` u ON u.id = l.user_id
            JOIN `types` t ON t.id = p.type_id
            WHERE p.user_id = '$user_id' AND (SELECT COUNT(*) FROM `likes` WHERE `post_id` = p.id ) > 0
            ORDER BY l.dt_add DESC;";
    $stmt = mysqli_prepare($con, $sql);
    $liked_user_posts = get_data($con, $stmt, false);
    if (!isset($liked_user_posts)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $liked_user_posts;
}

/**
 * Возвращает массив данных о подписчиках пользователя с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_following_users_of_user($con, $user_id)
{
    $sql = "SELECT u.* FROM `users` u 
            JOIN `follows` f ON u.id = f.following_user_id 
            WHERE f.follower_id = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $following_users_of_user = get_data($con, $stmt, false);
    if (!isset($following_users_of_user)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $following_users_of_user;
}

/**
 * Ставит/убирает лайк от пользователя с идентификатором $user_id посту с идентификатором $post_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 * @param int $post_id       - идентификатор поста.
 */
function toggle_like($con, $user_id, $post_id)
{
    $sql = "SELECT `id` FROM `likes` WHERE `user_id` = ? AND `post_id` = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $post_id);
    $like = get_data($con, $stmt, true);

    if ($like) {
        $sql = "DELETE FROM `likes` WHERE `user_id` = '" . intval($user_id) . "' 
                AND `post_id` = '" . intval($post_id) . "';";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        }
    } else {
        $sql = "INSERT INTO `likes` 
                SET `user_id` = '" . intval($user_id) . "', `post_id` = '" . intval($post_id) . "';";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        }
    }
}

/**
 * Возвращает массив с идентификаторами постов, лайкнутых пользователем с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_all_liked_post_ids_by_user($con, $user_id)
{
    $sql = "SELECT `post_id` FROM `likes` WHERE `user_id` = '$user_id';";
    $stmt = mysqli_prepare($con, $sql);
    $all_liked_post_ids_by_user_from_db = get_data($con, $stmt, false);
    if (!isset($all_liked_post_ids_by_user_from_db)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $all_liked_post_ids_by_user = [];
    $i = 0;
    foreach ($all_liked_post_ids_by_user_from_db as $item) {
        $all_liked_post_ids_by_user += [$i => $item['post_id']];
        $i++;
    }
    return $all_liked_post_ids_by_user;
}

/**
 * Возвращает массив с идентификаторами постов, репостнутых пользователем с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_all_reposted_post_ids_by_user($con, $user_id)
{
    $sql = "SELECT `repost_id` FROM `posts` WHERE `user_id` = '$user_id' AND `repost_id` IS NOT NULL;";
    $stmt = mysqli_prepare($con, $sql);
    $all_reposted_post_ids_by_user_from_db = get_data($con, $stmt, false);
    if (!isset($all_reposted_post_ids_by_user_from_db)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $all_reposted_post_ids_by_user = [];
    $i = 0;
    foreach ($all_reposted_post_ids_by_user_from_db as $item) {
        $all_reposted_post_ids_by_user += [$i => $item['repost_id']];
        $i++;
    }
    return $all_reposted_post_ids_by_user;
}

/**
 * Возвращает количество лайков поста с идентификатором $post_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста.
 *
 * @return int
 */
function count_likes_of_post($con, $post_id)
{
    $sql = "SELECT `id` FROM `likes` WHERE `post_id` = '" . intval($post_id) . "';";
    $result = mysqli_query($con, $sql);
    if (!isset($result)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $records_count = mysqli_num_rows($result);
    return $records_count;
}

/**
 * Возвращает количество комментариев поста с идентификатором $post_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста.
 *
 * @return int
 */
function count_comments_of_post($con, $post_id)
{
    $sql = "SELECT `id` FROM `comments` WHERE `post_id` = '" . intval($post_id) . "';";
    $result = mysqli_query($con, $sql);
    if (!isset($result)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $records_count = mysqli_num_rows($result);
    return $records_count;
}

/**
 * Возвращает количество репостов поста с идентификатором $post_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста.
 *
 * @return int
 */
function count_reposts_of_post($con, $post_id)
{
    $sql = "SELECT `id` FROM `posts` WHERE `repost_id` = '" . intval($post_id) . "';";
    $result = mysqli_query($con, $sql);
    if (!isset($result)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $records_count = mysqli_num_rows($result);
    return $records_count;
}

/**
 * Делает репост поста с идентификатором $post_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $post_id       - идентификатор поста.
 *
 * @return boolean
 */
function repost($con, $post_id)
{
    $post = get_post_for_repost($con, $post_id);
    $sql = "SELECT * FROM `posts` WHERE `user_id` = ? AND `repost_id` = ?;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', intval($_SESSION['user']['id']), $post_id);
    $is_reposted = get_data($con, $stmt, true);
    if ($post && !$is_reposted) {
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

/**
 * Возвращает массив данных о сообщениях пользователя с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_messages_of_user($con, $user_id)
{
    $sql = "SELECT u.avatar, u.login, u.id AS user_id, m.* FROM `users` u 
            JOIN `messages` m ON 
              CASE
                WHEN $user_id = m.sender_id
                  THEN u.id = m.receiver_id
                WHEN $user_id = m.receiver_id
                  THEN u.id = m.sender_id
              END;";
    $stmt = mysqli_prepare($con, $sql);
    $messages = get_data($con, $stmt, false);
    if (!isset($messages)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $messages;
}

/**
 * Возвращает массив данных о собеседниках пользователя с идентификатором $user_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $user_id       - идентификатор пользователя.
 *
 * @return array
 */
function get_interlocutors_of_user($con, $user_id)
{
    $sql = "SELECT u.avatar, u.login, u.id AS user_id, MAX(m.dt_add) AS last_message_time FROM `users` u 
            JOIN `messages` m ON 
              CASE
                WHEN $user_id = m.sender_id
                  THEN u.id = m.receiver_id
                WHEN $user_id = m.receiver_id
                  THEN u.id = m.sender_id
              END
            GROUP BY u.id ORDER BY last_message_time DESC;";
    $stmt = mysqli_prepare($con, $sql);
    $interlocutors = get_data($con, $stmt, false);
    if (!isset($interlocutors)) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    return $interlocutors;
}

/**
 * Возвращает количество непрочитанных сообщений от пользователя с идентификатором $sender_id к пользователю с
 * идентификатором $receiver_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $sender_id     - идентификатор отправителя.
 * @param int $receiver_id   - идентификатор получателя.
 *
 * @return int
 */
function count_unread_messages($con, $sender_id, $receiver_id)
{
    $sql
        = "SELECT id FROM `messages` 
           WHERE `sender_id` = " . intval($sender_id) . " 
           AND `receiver_id` = " . intval($receiver_id) . " 
           AND `is_read` = 0;";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $unread_messages_count = mysqli_num_rows($result);
    return $unread_messages_count;
}

/**
 * Возвращает количество всех сообщений непрочитанных пользователем с идентификатором $receiver_id.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $receiver_id   - идентификатор получателя.
 *
 * @return int
 */
function count_user_unread_messages($con, $receiver_id)
{
    $sql
        = "SELECT id FROM `messages` WHERE `receiver_id` = " . intval($receiver_id) . " AND `is_read` = 0;";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return false;
    }
    $unread_messages_count = mysqli_num_rows($result);
    return $unread_messages_count;
}

/**
 * Меняет значение is_read у всех непрочитанных сообщений от пользователя с идентификатором $sender_id к пользователю с
 * идентификатором $receiver_id со значения 0 на 1.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param int $sender_id     - идентификатор отправителя.
 * @param int $receiver_id   - идентификатор получателя.
 */
function read_all_user_messages($con, $sender_id, $receiver_id)
{
    $sql
        = "UPDATE `messages` SET `is_read` = 1 
           WHERE `is_read` = 0 
           AND `sender_id` = " . intval($sender_id) . " 
           AND `receiver_id`= " . intval($receiver_id) . ";";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
}
