<?php

include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: вход на сайт';
$form = include_once 'forms/auth-form.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = validate_form($form, $configs);

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors[$form['name']]) and $user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors[$form['name']]['password'] = 'Неверный пароль';
        }
    } elseif (!empty($_POST['email'])) {
        $errors[$form['name']]['email'] = 'Пользователь с таким email не найден';
    }

    if (!count($errors[$form['name']])) {
        header("Location: /feed.php?id=" . $_SESSION['user']['id']);
        exit();
    }
} else {
    if (isset($_SESSION['user'])) {
        header("Location: /feed.php?id=" . $_SESSION['user']['id']);
        exit();
    }
}

$layout = include_template('guest_layout.php', [
    'title' => $title,
    'form' => $form,
    'errors' => $errors ?? '',
]);
?>
<?= $layout; ?>
