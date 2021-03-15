<?php
include_once 'config.php';
include_once 'helpers.php';
include_once 'models.php';

$title = 'readme: регистрация';
$errors = [];

$form = [
    'title' => 'Форма регистрации',
    'name' => 'registration',
    'inputs' => [
        [
            'title' => 'Электронная почта',
            'required' => true,
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Укажите эл.почту',
            'field_type' => 'input',
        ],
        [
            'title' => 'Логин',
            'required' => true,
            'type' => 'text',
            'name' => 'login',
            'placeholder' => 'Укажите логин',
            'field_type' => 'input',
        ],
        [
            'title' => 'Пароль',
            'required' => true,
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Придумайте пароль',
            'field_type' => 'input',
        ],
        [
            'title' => 'Повтор пароля',
            'required' => true,
            'type' => 'password',
            'name' => 'password-repeat',
            'placeholder' => 'Повторите пароль',
            'field_type' => 'input',
        ],
        [
            'required' => false,
            'type' => 'file',
            'name' => 'userpic-file',
            'field_type' => 'input-file',
        ]
    ],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form['inputs'] as $input) {
        if (($input['type'] === 'text') && $input['required']) {
            $input['error'] = function ($input) {
                return validateFilled($input['name']);
            };
            if ($input['error']($input)) {
                $errors += [$input['name'] => $input['error']($input)];
            }
        } elseif ($input['type'] === 'email') {
            $input['error'] = function ($con, $input) {
                if (!validateFilled($input['name'])) {
                    if (!validateEmail($input['name'])) {
                        return validateUniqueEmail($con, $input['name']);
                    }
                    return validateEmail($input['name']);
                }
                return validateFilled($input['name']);
            };
            if ($input['error']($con, $input)) {
                $errors += [$input['name'] => $input['error']($con, $input)];
            }
        } elseif ($input['type'] === 'password' && $input['name'] === 'password') {
            $input['error'] = function ($input) {
                return validateFilled($input['name']);
            };
            if ($input['error']($input)) {
                $errors += [$input['name'] => $input['error']($input)];
            }
        } elseif ($input['type'] === 'password' && $input['name'] === 'password-repeat') {
            $input['error'] = function ($input) {
                return validatePassword($input['name'], 'password');
            };
            if ($input['error']($input)) {
                $errors += [$input['name'] => $input['error']($input)];
            }
        } elseif ($input['type'] === 'file') {
            $input['error'] = function ($input) {
                if (!empty($_FILES[$input['name']]) && !$_FILES[$input['name']]["error"]) {
                    return validateImageType($input['name']);
                }
                return false;
            };
            if ($input['error']($input)) {
                $errors += [$input['name'] => $input['error']($input)];
            }
        }
    }

    if (empty($errors)) {
        $db_email = $_POST['email'];
        $db_login = $_POST['login'];
        $db_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $db_avatar = '/uploads/users/' . basename($_FILES['userpic-file']['name']);
        $db_data = [
            'email' => $db_email,
            'login' => $db_login,
            'password' => $db_password,
            'avatar' => $db_avatar,
        ];

//        $new_user_id = add_user($con, $db_data);

        header("Location: index.php");
    }
}

$main_content = include_template('registration.php', [
    'form' => $form,
    'errors' => $errors,
]);
$layout = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
]);
?>
<?= $layout; ?>


