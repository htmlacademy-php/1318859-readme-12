<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([
            $stmt,
            $types
        ], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return false;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url, $width, $height)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="' . $width . '" height="' . $height . '" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] === '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if (isset($parts['host']) && $parts['host'] === 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [
        ['minutes' => 59],
        ['hours' => 23],
        ['days' => 6],
        ['weeks' => 4],
        ['months' => 11]
    ];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Обрезает текст до необходимого количества символов
 *
 * @param string $text Текст в виде строки, который нужно обрезать
 * @param int $length Длина обрезанного текста
 *
 * @return string Обрезанный текст в виде строки
 */
function crop_text($text, $length = 300)
{
    $words = explode(' ', $text);
    $sum_length = 0;
    $words_cropped = [];

    for ($i = 0; $i < count($words); $i++) {
        if ($sum_length > $length) {
            break;
        }
        $words_cropped[$i] = $words[$i];
        $sum_length += (mb_strlen($words[$i]) + 1);
    }

    $text_cropped = implode(' ', $words_cropped);
    if ($text !== $text_cropped) {
        return $text_cropped;
    }

    return false;
}

/**
 * Выводит время относительно текущего момента
 *
 * @param string $date - дата в формате 'Y-m-d H:i:s'
 *
 * @return string Текст в виде строки
 */
function print_date_diff($date)
{
    $first_date = date_create($date);
    $now = date_create('now');
    $date_diff = date_diff($now, $first_date);

    foreach ($date_diff as $period => $amount) {
        if ($amount !== 0) {
            switch ($period) {
                case 'y':
                    return "$amount " . get_noun_plural_form($amount, 'год', 'года', 'лет');
                    break;
                case 'm':
                    return "$amount " . get_noun_plural_form($amount, 'месяц', 'месяца', 'месяцев');
                    break;
                case 'd':
                    return "$amount " . get_noun_plural_form($amount, 'день', 'дня', 'дней');
                    break;
                case 'h':
                    return "$amount " . get_noun_plural_form($amount, 'час', 'часа', 'часов');
                    break;
                case 'i':
                    return "$amount " . get_noun_plural_form($amount, 'минуту', 'минуты', 'минут');
                    break;
                default:
                    return 'несколько секунд';
            }
        }
    }
}

/**
 * Получает данные из базы данных
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает объект с данными, иначе - false.
 * @param string $stmt - подготовленное выражение в виде строки.
 * @param bool $is_row - флаг, означающий получение одной строки из таблицы БД.
 *
 * @return array  В зависимости от $is_row одномерный или двумерный массив.
 */
function get_data($con, $stmt, $is_row)
{
    if ($con === false) {
        return print("Ошибка подключения: " . mysqli_connect_error());
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($is_row) {
        $data = mysqli_fetch_assoc($result);
    } else {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $data;
}

/**
 * Проверяет заполненность поля формы и выдаёт текст ошибки, если поле не заполнено.
 * @param string $name Значение атрибута 'name' поля формы
 * @return string
 */
function validateFilled($name)
{
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }
}

/**
 * Проверяет, находится ли длина строки в пределах указанных значений и возвращает текст ошибки, если длина выходит за эти пределы.
 * @param string $name Значение атрибута 'name' поля формы
 * @param int $min Минимальная длиня строки
 * @param int $max Максимальная длиня строки
 * @return string
 */
function isCorrectMinLengthComment($name, $minLength)
{
    $len = strlen(trim($_POST[$name]));

    if ($len < $minLength) {
        return "Комментарий должен быть не менее $minLength символов";
    }
}

/**
 * Проверяет значение поля формы на соответствие корректному URL-адресу, если не соответствует, выдаёт текст ошибки.
 * @param string $name Значение атрибута 'name' поля формы
 * @return string
 */
function validateUrl($name)
{
    if (!filter_var($_POST[$name], FILTER_VALIDATE_URL)) {
        return "Значение поля должно быть корректным URL-адресом";
    }
    return false;
}

/**
 * Проверяет значение поля формы на соответствие корректному email-адресу, если не соответствует, выдаёт текст ошибки.
 * @param string $name Значение атрибута 'name' поля формы
 * @return string
 */
function validateEmail($name)
{
    if (!filter_var($_POST[$name], FILTER_VALIDATE_EMAIL)) {
        return "Значение поля должно быть корректным email-адресом";
    }
    return false;
}

/**
 * Проверяет введённый e-mail на уникальность, если не уникален, выдаёт текст ошибки.
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает объект с данными, иначе - false.
 * @param string $name Значение атрибута 'name' поля формы
 * @return string
 */
function validateUniqueEmail($con, $name)
{
    $email = mysqli_real_escape_string($con, $_POST[$name]);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {
        return "Пользователь с таким email уже зарегистрирован";
    }
    return false;
}

/**
 * Проверяет совпадене значений в полях формы "Пароль" и "Повторить пароль", если не совпадают, выдаёт текст ошибки.
 * @param string $password_repeat Значение из поля "Повторите пароль"
 * @param string $password Значение из поля "Пароль"
 * @return string
 */
function validatePassword($password_repeat, $password)
{
    if ($_POST[$password_repeat] !== $_POST[$password]) {
        return "Пароли не совпадают.";
    }
    return false;
}

/**
 * Проверяет тип файла, загружаемого по ссылке. Если это не изображение, возвращает текст ошибки, иначе проверяет содежримое файла.
 * @param string $name Значение атрибута 'name' поля формы
 * @return string
 */
function validateImageTypeFromUrl($name)
{
    $fileType = strrchr($_POST[$name], '.');
    $validTypes = [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif'
    ];

    $i = 0;
    while ($i < count($validTypes)) {
        if ($fileType === $validTypes[$i]) {
            return validateImageUrlContent($name);
        }
        $i++;
    }
    return "Файл должен быть картинкой.";
}

/**
 * Проверяет содержимое файла, загружаемого по ссылке. Если не находит изображение, возвращает текст ошибки, иначе загружает изображение в папку /uploads.
 * @param string $name Значение атрибута 'name' поля формы
 * @return string
 */
function validateImageUrlContent($name)
{
    $content = file_get_contents($_POST[$name]);
    if (!$content) {
        return "Не удалось загрузить файл. Пожалуйста, проверьте ещё раз указанный адрес.";
    }
    moveImageFromUrl($name);
    return false;
}

/**
 * Загружает изображение в папку /uploads.
 * @param string $name Значение атрибута 'name' поля формы
 */
function moveImageFromUrl($name)
{
    $fileName = strrchr($_POST[$name], '/');
    $local = __DIR__ . '/uploads' . $fileName;
    file_put_contents($local, file_get_contents($_POST[$name]));
}

/**
 * Проверяет тип файла, загружаемого с компьютера. Если это не изображение, возвращает текст ошибки, иначе загружает изображение в папку /uploads.
 * @param string $name Значение атрибута 'name' поля формы
 * @return string
 */
function validateImageType($name)
{
    $fileType = $_FILES[$name]["type"];
    $validTypes = [
        'image/png',
        'image/jpeg',
        'image/gif'
    ];
    $i = 0;
    while ($i < count($validTypes)) {
        if ($fileType === $validTypes[$i]) {
            return moveUploadedImage($name);
        }
        $i++;
    }
    return "Формат загруженного файла должен быть изображением одного из следующих типов: png, jpeg, gif.";
}

/**
 * Перемещает загруженное с компьютера изображение из временной папки в папку /uploads и возвращает false. Если перемещение не удалось, возвращает текст ошибки.
 * @param string $name Значение атрибута 'name' поля формы
 * @return false
 */
function moveUploadedImage($name)
{
    if ($name === 'userpic-file') {
        $uploaddir = __DIR__ . '/uploads/users/';
    } else {
        $uploaddir = __DIR__ . '/uploads/';
    }
    $uploadfile = $uploaddir . time() . '-' . $_FILES[$name]['name'];

    if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
        move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile);
        return false;
    }
    return "Не удалось загрузить файл.";
}

/**
 * Возвращает массив тегов поста.
 * @param string $name Значение атрибута 'name' поля формы
 * @return array
 */
function getTagsFromPost($name)
{
    preg_match_all('/([\w0-9_])+/u', $_POST[$name], $postTags);
    return $postTags[0];
}

/**
 * Возвращает массив ошибок валидации формы.
 * @param array $form Массив со свойствами валидируемой формы
 * @param array $configs Массив дополнительных параметров для проверок
 * @return array
 */
function validate_form($form, $configs)
{
    $errors = [$form['name'] => []];
    foreach ($form['inputs'] as $input) {
        foreach ($input['checks'] as $check) {
            $error = $check($input, $configs);
            if ($error) {
                $errors[$form['name']] += [$input['name'] => $error];
            }
        }
    }
    return $errors;
}

/**
 * Отправляет уведомление на почту о новом подписчике.
 * @param array $follower Данные о подписчике
 * @param array $following Данные об адресате
 */
function sendSubscribeNotification($follower, $following)
{
    include_once 'vendor/autoload.php';

    $transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
    $transport->setUsername("3aa53903ba72c2");
    $transport->setPassword("d23b1bfd88dbec");

    $mailer = new Swift_Mailer($transport);

    $email = [
        'subject' => 'У вас новый подписчик',
        'sender_email' => ['keks@phpdemo.ru' => 'Readme'],
        'addressee_emails' => [$following['email']],
        'message_content' => 'Здравствуйте, ' . $following['login'] . '. На вас подписался новый пользователь ' . $follower['login'] . '. Вот ссылка на его профиль: ' . ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/profile.php?id=' . $follower['id'],
    ];

    $message = new Swift_Message();
    $message->setSubject($email['subject']);
    $message->setFrom($email['sender_email']);
    $message->setBcc(['mixa.awesome@gmail.com']);

    $msg_content = $email['message_content'];
    $message->setBody($msg_content, 'text/html');

    $result = $mailer->send($message);

    if (!$result) {
        print("Не удалось отправить рассылку");
    }
}

/**
 * Отправляет уведомление о появлении нового поста на почту всем подписчикам.
 * @param array $post Данные о посте
 * @param array $followers данные об адресатах
 */
function sendNewPostNotification($post, $followers)
{
    include_once 'vendor/autoload.php';

    $transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
    $transport->setUsername("3aa53903ba72c2");
    $transport->setPassword("d23b1bfd88dbec");

    $mailer = new Swift_Mailer($transport);

    foreach ($followers as $follower) {
        $email = [
            'subject' => 'Новая публикация от пользователя ' . $_SESSION['user']['login'],
            'sender_email' => ['keks@phpdemo.ru' => 'Readme'],
            'addressee_emails' => [$follower['email']],
            'message_content' => 'Здравствуйте, ' . $follower['login'] . '. Пользователь ' . $_SESSION['user']['login'] . ' только что опубликовал новую запись "' . $post['title'] . '". Посмотрите её на странице пользователя: ' . ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/profile.php?id=' . $_SESSION['user']['id'],
        ];

        $message = new Swift_Message();
        $message->setSubject($email['subject']);
        $message->setFrom($email['sender_email']);
        $message->setBcc(['mixa.awesome@gmail.com']);

        $msg_content = $email['message_content'];
        $message->setBody($msg_content, 'text/html');

        $result = $mailer->send($message);

        if (!$result) {
            print("Не удалось отправить рассылку");
        }
    }
}
