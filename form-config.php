<?php
$errors = [
    'auth-form' => [],
    'reg-form' => [],
    'photo-form' => [],
    'video-form' => [],
    'text-form' => [],
    'quote-form' => [],
    'link-form' => [],
];
$checks = [
    'auth-form' => [
        'email' => [
            0 => function ($input) {
                return validateFilled($input['name']);
            }
        ],
        'password' => [
            0 => function ($input) {
                return validateFilled($input['name']);
            }
        ],
    ],
    'reg-form' => [
        'email' => [
            0 => function ($input) {
                return validateFilled($input['name']);
            },
            1 => function ($input) {
                return validateEmail($input['name']);
            },
            2 => function ($con, $input) {
                return validateUniqueEmail($con, $input['name']);
            }
        ],
        'login' => [
            0 => function ($input) {
                return validateFilled($input['name']);
            }
        ],
        'password' => [
            0 => function ($input) {
                return validateFilled($input['name']);
            }
        ],
        'password-repeat' => [
            0 => function ($input) {
                return validatePassword($input['name'], 'password');
            }
        ],
        'userpic-file' => [
            0 => function ($input) {
                return validateImageType($input['name']);
            }
        ],
    ],
    'photo-form' => [
        'heading' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
        'url' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            },
            1 => function ($current_tab, $input) {
                return validateUrl($current_tab . '-' . $input['name']);
            },
            2 => function ($current_tab, $input) {
                return validateImageTypeFromUrl($current_tab . '-' . $input['name']);
            }
        ],
        'userpic-file' => [
            0 => function ($current_tab, $input) {
                return validateImageType($current_tab . '-' . $input['name']);
            }
        ],
    ],
    'video-form' => [
        'heading' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
        'url' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            },
            1 => function ($current_tab, $input) {
                return validateUrl($current_tab . '-' . $input['name']);
            },
            2 => function ($current_tab, $input) {
                return check_youtube_url($_POST[$current_tab . '-' . $input['name']]);
            }
        ],
    ],
    'text-form' => [
        'heading' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
        'post' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
    ],
    'quote-form' => [
        'heading' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
        'text' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
        'author' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
    ],
    'link-form' => [
        'heading' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            }
        ],
        'url' => [
            0 => function ($current_tab, $input) {
                return validateFilled($current_tab . '-' . $input['name']);
            },
            1 => function ($current_tab, $input) {
                return validateUrl($current_tab . '-' . $input['name']);
            }
        ],
    ],
];

function validate_form($form, $checks, $errors) {
    foreach ($form['inputs'] as $input) {
        foreach ($checks[$form['name']][$input['name']] as $check) {
            if ($check) {
                $errors[$form['name']] += [$input['name'] => $check];
            }
        }
    }
}
