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
            'checks' => [
                0 => function ($input) {
                    return validateFilled($input['name']);
                },
                1 => function ($input) {
                    return validateEmail($input['name']);
                },
                2 => function ($con, $input) {
                    return validateUniqueEmail($con, $input['name']);
                }
            ]
        ],
        [
            'title' => 'Логин',
            'required' => true,
            'type' => 'text',
            'name' => 'login',
            'placeholder' => 'Укажите логин',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input) {
                    return validateFilled($input['name']);
                }
            ]
        ],
        [
            'title' => 'Пароль',
            'required' => true,
            'type' => 'password',
            'name' => 'password',
            'placeholder' => 'Придумайте пароль',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input) {
                    return validateFilled($input['name']);
                }
            ]
        ],
        [
            'title' => 'Повтор пароля',
            'required' => true,
            'type' => 'password',
            'name' => 'password-repeat',
            'placeholder' => 'Повторите пароль',
            'field_type' => 'input',
            'checks' => [
                0 => function ($input) {
                    return validatePassword($input['name'], 'password');
                }
            ]
        ],
        [
            'required' => false,
            'type' => 'file',
            'name' => 'userpic-file',
            'field_type' => 'input-file',
            'checks' => [
                0 => function ($input) {
                    return validateImageType($input['name']);
                }
            ]
        ]
    ],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form['inputs'] as $input) {
        if (($input['type'] === 'text') && $input['required']) {
            if ($input['checks'][0]($input)) {
                $errors += [$input['name'] => $input['checks'][0]($input)];
            }
        } elseif ($input['type'] === 'email') {
            if ($input['checks'][0]($input)) {
                $errors += [$input['name'] => $input['checks'][0]($input)];
            } elseif ($input['checks'][1]($input)) {
                $errors += [$input['name'] => $input['checks'][1]($input)];
            } elseif ($input['checks'][2]($con, $input)) {
                $errors += [$input['name'] => $input['checks'][2]($con, $input)];
            }
        } elseif ($input['type'] === 'password' && $input['name'] === 'password') {
            if ($input['checks'][0]($input)) {
                $errors += [$input['name'] => $input['checks'][0]($input)];
            }
        } elseif ($input['type'] === 'password' && $input['name'] === 'password-repeat') {
            if ($input['checks'][0]($input)) {
                $errors += [$input['name'] => $input['checks'][0]($input)];
            }
        } elseif ($input['type'] === 'file') {
            if (!empty($_FILES[$input['name']]) && $_FILES[$input['name']]["error"] !== 4) {
                if ($input['checks'][0]($input)) {
                    $errors += [$input['name'] => $input['checks'][0]($input)];
                }
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

        $new_user_id = add_user($con, $db_data);

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


