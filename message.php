<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: личные сообщения';
$messages = get_messages_of_user($con, intval($_SESSION['user']['id']));
$interlocutors = get_interlocutors_of_user($con, intval($_SESSION['user']['id']));

foreach ($interlocutors as $key => $interlocutor) {
    $interlocutors[$key]['unread_messages'] = count_unread_messages(
        $con,
        $interlocutor['user_id'],
        intval($_SESSION['user']['id'])
    );
    foreach ($messages as $message) {
        if ($message['dt_add'] === $interlocutor['last_message_time']
            && $message['sender_id'] === $interlocutor['user_id']
        ) {
            $interlocutors[$key]['last_message'] = $message['content'];
            $interlocutors[$key]['is_last_message_mine'] = false;
        } elseif ($message['dt_add'] === $interlocutor['last_message_time']
            && $message['receiver_id'] === $interlocutor['user_id']
        ) {
            $interlocutors[$key]['last_message'] = $message['content'];
            $interlocutors[$key]['is_last_message_mine'] = true;
        }
    }
}

$users = get_all_users($con);
$existed_users_ids = [];
for ($i = 0; $i < count($users); $i++) {
    $existed_users_ids[$i] = $users[$i]['id'];
}
$existed_interlocutors_ids = [];
for ($i = 0; $i < count($interlocutors); $i++) {
    $existed_interlocutors_ids[$i] = $interlocutors[$i]['user_id'];
}

if (!isset($_GET["id"])) {
    $main_content = include_template('messages.php', [
        'con'           => $con,
        'interlocutors' => $interlocutors,
    ]);
} elseif (isset($_GET["id"]) && in_array(intval($_GET["id"]), $existed_users_ids)
    && ($_GET["id"] !== $_SESSION['user']['id'])
) {
    $id = intval($_GET["id"]);
    $current_interlocutor = [];
    foreach ($interlocutors as $key => $interlocutor) {
        if ($interlocutor['user_id'] === $id) {
            $current_interlocutor = $interlocutors[$key];
        }
    }

    if ($current_interlocutor['unread_messages'] > 0) {
        read_all_user_messages($con, $id, intval($_SESSION['user']['id']));
        header("Location: message.php?id=$id");
        exit();
    }

    $form = include_once 'forms/message-form.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== intval($_SESSION['user']['id'])) {
        $errors = validate_form($form, $configs);
        if (empty($errors[$form['name']])) {
            $db_content = htmlspecialchars($_POST['message']);
            $db_sender_id = intval($_SESSION['user']['id']);
            $db_receiver_id = intval($_POST['id']);
            $db_data = [
                'content'     => $db_content,
                'sender_id'   => $db_sender_id,
                'receiver_id' => $db_receiver_id,
            ];
            add_message($con, $db_data);
            header("Location: message.php?id=$id");
        }
    }

    $main_content = include_template('messages.php', [
        'con'                       => $con,
        'form'                      => $form,
        'errors'                    => $errors ?? '',
        'id'                        => $id,
        'current_interlocutor'      => $current_interlocutor,
        'messages'                  => $messages,
        'interlocutors'             => $interlocutors,
        'existed_interlocutors_ids' => $existed_interlocutors_ids,
    ]);
} else {
    header('HTTP/1.0 404 not found');
    $main_content = include_template('404.php');
}

$layout = include_template('layout.php', [
    'main_content'                       => $main_content,
    'user_name'                          => $_SESSION['user']['login'],
    'user_avatar'                        => $_SESSION['user']['avatar'],
    'user_id'                            => $_SESSION['user']['id'],
    'title'                              => $title,
    'nav_links'                          => $configs['nav_links'],
    'count_session_user_unread_messages' => count_user_unread_messages($con, intval($_SESSION['user']['id'])),
]);

echo $layout;
