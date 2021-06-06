<?php
include_once 'config.php';
include_once 'init.php';
include_once 'helpers.php';
include_once 'models.php';

if (isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$title = 'readme: регистрация';

$form = include_once 'forms/reg-form.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_form($form, $configs);
    if (empty($errors[$form['name']])) {
        $db_email = $_POST['email'];
        $db_login = $_POST['login'];
        $db_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $db_avatar = '/uploads/users/' . time() . '-' . $_FILES['userpic-file']['name'];
        $db_data = [
            'email' => $db_email,
            'login' => $db_login,
            'password' => $db_password,
            'avatar' => $db_avatar,
        ];

        $new_user_id = add_user($con, $db_data);

        header("Location: index.php");
    }
}

$main_content = include_template('registration.php', [
    'form' => $form,
    'errors' => $errors ?? '',
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
]);
?>
<?= $layout; ?>


