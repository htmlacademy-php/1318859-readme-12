<?php

include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: регистрация';
$errors = [];
$form = [
    'title' => 'Авторизация',
    'inputs' => [
        [
            'title' => 'Email',
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Email',
            'icon' => [
                'name' => 'user',
                'width' => '19',
                'height' => '18',
            ]
        ],
        [
            'title' => 'Пароль',
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Пароль',
            'icon' => [
                'name' => 'password',
                'width' => '16',
                'height' => '20',
            ]
        ],
    ],
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach ($form['inputs'] as $input) {
        $input['error'] = function ($input) {
            return validateFilled($input['name']);
        };
        if ($input['error']($input)) {
            $errors += [$input['name'] => $input['error']($input)];
        }
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } elseif (!empty($_POST['email'])) {
        $errors['email'] = 'Пользователь с таким email не найден';
    }

    if (!count($errors)) {
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
    'errors' => $errors,
]);
?>

<?= $layout; ?>
