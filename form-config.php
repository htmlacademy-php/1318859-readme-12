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
            2 => function ($input, $configs) {
                return validateUniqueEmail($configs['con'], $input['name']);
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
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'url' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            },
            1 => function ($input, $configs) {
                return validateUrl($configs['current_tab'] . '-' . $input['name']);
            },
            2 => function ($input, $configs) {
                return validateImageTypeFromUrl($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'userpic-file' => [
            0 => function ($input, $configs) {
                return validateImageType($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'tags' => [],
    ],
    'video-form' => [
        'heading' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'url' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            },
            1 => function ($input, $configs) {
                return validateUrl($configs['current_tab'] . '-' . $input['name']);
            },
            2 => function ($input, $configs) {
                return check_youtube_url($_POST[$configs['current_tab'] . '-' . $input['name']]);
            }
        ],
        'tags' => [],
    ],
    'text-form' => [
        'heading' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'post' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'tags' => [],
    ],
    'quote-form' => [
        'heading' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'text' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'author' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'tags' => [],
    ],
    'link-form' => [
        'heading' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'url' => [
            0 => function ($input, $configs) {
                return validateFilled($configs['current_tab'] . '-' . $input['name']);
            },
            1 => function ($input, $configs) {
                return validateUrl($configs['current_tab'] . '-' . $input['name']);
            }
        ],
        'tags' => [],
    ],
];

function validate_form($form, $checks, $errors, $configs) {
    foreach ($form['inputs'] as $input) {
        foreach ($checks[$form['name']][$input['name']] as $check) {
            if ($form['name'] === 'photo-form' && [$input['name']] === 'url') {
                if (empty($_FILES["photo-userpic-file"]) || $_FILES["photo-userpic-file"]["error"] === 4) {
                    if ($check($input, $configs)) {
                        $errors[$form['name']] += [$input['name'] => $check($input, $configs)];
                    }
                }
            } elseif ([$input['name']] === 'userpic-file') {
                if (!empty($_FILES[$configs['current_tab'] . '-' . $input['name']]) && $_FILES[$configs['current_tab'] . '-' . $input['name']]["error"] !== 4) {
                    if ($check($input, $configs)) {
                        $errors[$form['name']] += [$input['name'] => $check($input, $configs)];
                    }
                }
            } else {
                if ($check($input, $configs)) {
                    $errors[$form['name']] += [$input['name'] => $check($input, $configs)];
                }
            }
        }
    }
    return $errors;
}
