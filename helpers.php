<?php
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
 * @param int    $number Число, по которому вычисляем форму множественного числа
 * @param string $one    Форма единственного числа: яблоко, час, минута
 * @param string $two    Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many   Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form($number, $one, $two, $many)
{
    $number = (int) $number;
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
 *
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array  $data Ассоциативный массив с данными для шаблона
 *
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
 *
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers
        = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
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
 *
 * @param string $youtube_url Ссылка на youtube видео
 *
 * @return string
 */
function embed_youtube_video($youtube_url, $width, $height)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="' . $width . '" height="' . $height . '" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 *
 * @param string $youtube_url Ссылка на youtube видео
 *
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
 *
 * @param string $youtube_url Ссылка на youtube видео
 *
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
 * Обрезает текст до необходимого количества символов
 *
 * @param string $text   Текст в виде строки, который нужно обрезать
 * @param int    $length Длина обрезанного текста
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
 * Выводит время (или дату) последнего сообщения
 *
 * @param string $last_message_time - дата в формате 'Y-m-d H:i:s'
 *
 * @return string Текст в виде строки
 */
function print_last_message_date($last_message_time)
{
    $translate_months = [
        'Jan' => ' Янв.',
        'Feb' => ' Фев.',
        'Mar' => ' Марта',
        'Apr' => ' Апр.',
        'May' => ' Мая',
        'Jun' => ' Июня',
        'Jul' => ' Июля',
        'Aug' => ' Авг.',
        'Sep' => ' Сент.',
        'Okt' => ' Окт.',
        'Nov' => ' Нояб.',
        'Dec' => ' Дек.',
    ];
    $last_message_date = date_create($last_message_time);
    $today_start = date_create('today');
    if ($last_message_date >= $today_start) {
        return date_format($last_message_date, 'H:i');
    }
    $en_month = date_format($last_message_date, 'M');
    return date_format($last_message_date, "j" . $translate_months[$en_month]);
}

/**
 * Получает данные из базы данных
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param string $stmt       - подготовленное выражение в виде строки.
 * @param bool   $is_row     - флаг, означающий получение одной строки из таблицы БД.
 *
 * @return array  В зависимости от $is_row одномерный или двумерный массив.
 */
function get_data($con, $stmt, $is_row)
{
    if (gettype($stmt) === 'boolean') {
        return [];
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
        return [];
    }
    if ($is_row) {
        $data = mysqli_fetch_assoc($result);
    } else {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $data;
}

/**
 * Проверяет заполненность поля формы и выдаёт текст ошибки, если поле не заполнено.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return string
 */
function validate_filled($name)
{
    return (empty(trim($_POST[$name]))) ? "Это поле должно быть заполнено" : false;
}

/**
 * Сравнивает длину строки с максимально допустимой длиной и возвращает текст ошибки, если длина больше максимально
 * допустимой.
 *
 * @param string $name       Значение атрибута 'name' поля формы
 * @param int    $max_length Максимальная длина строки
 *
 * @return string|bool
 */
function validate_max_length($name, $max_length)
{
    $len = strlen(trim($_POST[$name]));

    return ($len > $max_length) ? "Количество символов не должно превышать $max_length" : false;
}

/**
 * Сравнивает длину имени файла изображения с максимально допустимой длиной и возвращает текст ошибки, если длина
 * больше максимально допустимой.
 *
 * @param string $name       Значение атрибута 'name' поля формы
 * @param int    $max_length Максимальная длина строки
 *
 * @return string|bool
 */
function validate_max_image_name_length($name, $max_length)
{
    $len = strlen(trim($_FILES[$name]['name']));

    return ($len > $max_length)
        ? "Количество символов в имени файла изображения не должно превышать $max_length" : false;
}

/**
 * Сравнивает длину введённого комментария с минимально допустимой длиной и возвращает текст ошибки, если длина меньше
 * минимально допустимой.
 *
 * @param string $name       Значение атрибута 'name' поля формы
 * @param int    $min_length Минимальная длина строки
 *
 * @return string|bool
 */
function is_correct_min_length_comment($name, $min_length)
{
    $len = strlen(trim($_POST[$name]));

    return ($len < $min_length) ? "Комментарий должен быть не менее $min_length символов" : false;
}

/**
 * Проверяет значение поля формы на соответствие корректному URL-адресу, если не соответствует, выдаёт текст ошибки.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return string|bool
 */
function validate_url($name)
{
    return (!filter_var($_POST[$name], FILTER_VALIDATE_URL))
        ? "Значение поля должно быть корректным URL-адресом" : false;
}

/**
 * Проверяет значение поля формы на соответствие корректному email-адресу, если не соответствует, выдаёт текст ошибки.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return string|bool
 */
function validate_email($name)
{
    return (!filter_var($_POST[$name], FILTER_VALIDATE_EMAIL))
        ? "Значение поля должно быть корректным email-адресом" : false;
}

/**
 * Проверяет введённый e-mail на уникальность, если не уникален, выдаёт текст ошибки.
 *
 * @param object(false) $con - результат работы mysqli_connect(). При успешном подключении к базе данных возвращает
 *                           объект с данными, иначе - false.
 * @param string $name       Значение атрибута 'name' поля формы
 *
 * @return string|bool
 */
function validate_unique_email($con, $name)
{
    $email = mysqli_real_escape_string($con, $_POST[$name]);
    $sql = "SELECT `id` FROM `users` WHERE `email` = '$email'";
    $res = mysqli_query($con, $sql);

    return (mysqli_num_rows($res) > 0) ? "Пользователь с таким email уже зарегистрирован" : false;
}

/**
 * Проверяет совпадене значений в полях формы "Пароль" и "Повторить пароль", если не совпадают, выдаёт текст ошибки.
 *
 * @param string $password_repeat Значение из поля "Повторите пароль"
 * @param string $password        Значение из поля "Пароль"
 *
 * @return string|bool
 */
function validate_password($password_repeat, $password)
{
    return ($_POST[$password_repeat] !== $_POST[$password]) ? "Пароли не совпадают." : false;
}

/**
 * Проверяет тип файла, загружаемого по ссылке. Если это не изображение, возвращает текст ошибки, иначе проверяет
 * содежримое файла.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return string
 */
function validate_image_type_from_url($name)
{
    $file_type = strrchr($_POST[$name], '.');
    $valid_types = [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
    ];

    $i = 0;
    while ($i < count($valid_types)) {
        if ($file_type === $valid_types[$i]) {
            return validate_image_url_content($name);
        }
        $i++;
    }
    return "Файл должен быть картинкой.";
}

/**
 * Проверяет содержимое файла, загружаемого по ссылке. Если не находит изображение, возвращает текст ошибки, иначе
 * загружает изображение в папку /uploads.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return string|bool
 */
function validate_image_url_content($name)
{
    $content = file_get_contents($_POST[$name]);
    if (!$content) {
        return "Не удалось загрузить файл. Пожалуйста, проверьте ещё раз указанный адрес.";
    }
    move_image_from_url($name);
    return false;
}

/**
 * Загружает изображение в папку /uploads.
 *
 * @param string $name Значение атрибута 'name' поля формы
 */
function move_image_from_url($name)
{
    $file_name = strrchr($_POST[$name], '/');
    $local = __DIR__ . '/uploads' . $file_name;
    file_put_contents($local, file_get_contents($_POST[$name]));
}

/**
 * Проверяет тип файла, загружаемого с компьютера. Если это не изображение, возвращает текст ошибки, иначе загружает
 * изображение в папку /uploads.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return string
 */
function validate_image_type($name)
{
    $file_type = $_FILES[$name]["type"];
    $valid_types = [
        'image/png',
        'image/jpeg',
        'image/gif',
    ];
    $i = 0;
    while ($i < count($valid_types)) {
        if ($file_type === $valid_types[$i]) {
            return move_uploaded_image($name);
        }
        $i++;
    }
    return "Формат загруженного файла должен быть изображением одного из следующих типов: png, jpeg, gif.";
}

/**
 * Перемещает загруженное с компьютера изображение из временной папки в папку /uploads и возвращает false. Если
 * перемещение не удалось, возвращает текст ошибки.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return string|bool
 */
function move_uploaded_image($name)
{
    if ($name === 'userpic-file') {
        $upload_dir = __DIR__ . '/uploads/users/';
    } else {
        $upload_dir = __DIR__ . '/uploads/';
    }
    $upload_file = $upload_dir . time() . '-' . $_FILES[$name]['name'];

    if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
        move_uploaded_file($_FILES[$name]['tmp_name'], $upload_file);
        return false;
    }
    return "Не удалось загрузить файл.";
}

/**
 * Возвращает массив тегов поста.
 *
 * @param string $name Значение атрибута 'name' поля формы
 *
 * @return array
 */
function get_tags_from_post($name)
{
    preg_match_all('/([\w0-9_])+/u', $_POST[$name], $post_tags);
    return $post_tags[0];
}

/**
 * Сравнивает длину каждого тега с максимально допустимой длиной и возвращает текст ошибки, если длина хотя бы одного
 * из тегов больше максимально допустимой.
 *
 * @param string $name       Значение атрибута 'name' поля формы
 * @param int    $max_length Максимальная длина строки
 *
 * @return string|bool
 */
function validate_max_tag_name_length($name, $max_length)
{
    $tags = get_tags_from_post($name);
    if (count($tags) > 0) {
        foreach ($tags as $tag) {
            $len = strlen($tag);
            if ($len > $max_length) {
                return "Тег должен быть короче $max_length символов";
            }
        }
    }
    return false;
}

/**
 * Возвращает массив ошибок валидации формы.
 *
 * @param array $form    Массив со свойствами валидируемой формы
 * @param array $configs Массив дополнительных параметров для проверок
 *
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
 * Подготавливает объект Swift_SmtpTransport для подключения к почте.
 *
 * @param string $host           адрес почтового сервиса
 * @param int    $port           порт почтового сервиса
 * @param string $login          логин пользователя
 * @param string $password       пароль пользователя
 *                               *
 *
 * @return \Swift_Transport
 */
function prepare_mail_settings($host, $port, $login, $password)
{
    include_once 'vendor/autoload.php';

    $transport = new Swift_SmtpTransport($host, $port);
    $transport->setUsername($login);
    $transport->setPassword($password);

    return $transport;
}

/**
 * Отправляет уведомление на почту о новом подписчике.
 *
 * @param array $follower  Данные о подписчике
 * @param array $following Данные об адресате
 */
function send_subscribe_notification($follower, $following)
{
    $transport = prepare_mail_settings(SMTP_HOST, SMTP_PORT, SMTP_LOGIN, SMTP_PASSWORD);
    $mailer = new Swift_Mailer($transport);

    $email = [
        'subject'          => 'У вас новый подписчик',
        'sender_email'     => ['keks@phpdemo.ru' => 'Readme'],
        'addressee_emails' => [htmlspecialchars($following['email'] ?? '')],
        'message_content'  => 'Здравствуйте, ' . htmlspecialchars($following['login'] ?? '')
            . '. На вас подписался новый пользователь ' . htmlspecialchars($follower['login'] ?? '')
            . '. Вот ссылка на его профиль: ' . ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://'
            . $_SERVER['HTTP_HOST'] . '/profile.php?id=' . $follower['id'] ?? '',
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
 *
 * @param array $post      Данные о посте
 * @param array $followers данные об адресатах
 */
function send_new_post_notification($post, $followers)
{
    $transport = prepare_mail_settings(SMTP_HOST, SMTP_PORT, SMTP_LOGIN, SMTP_PASSWORD);
    $mailer = new Swift_Mailer($transport);

    foreach ($followers as $follower) {
        $email = [
            'subject'          => 'Новая публикация от пользователя ' . htmlspecialchars($_SESSION['user']['login'] ??
                    ''),
            'sender_email'     => ['keks@phpdemo.ru' => 'Readme'],
            'addressee_emails' => [htmlspecialchars($follower['email'] ?? '')],
            'message_content'  => 'Здравствуйте, ' . htmlspecialchars($follower['login'] ?? '') . '. Пользователь '
                . htmlspecialchars($_SESSION['user']['login'] ?? '') . ' только что опубликовал новую запись "'
                . htmlspecialchars($post['title'] ?? '') . '". Посмотрите её на странице пользователя: '
                . ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/profile.php?id='
                . $_SESSION['user']['id'] ?? '',
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


/**
 * Формирует массив данных поста для занесения в базу данных в зависимости от типа поста.
 *
 * @param string $current_tab Тип поста
 * @param array  $db_data     Массив данных о посте, не зависящий от типа поста
 *
 * @return array
 */
function build_post_data($current_tab, &$db_data)
{
    if ($current_tab === 'photo') {
        if (isset($_FILES['photo-userpic-file']['name'])) {
            $db_post_image = '/uploads/' . time() . '-' . $_FILES['photo-userpic-file']['name'];
        } else {
            $db_post_image = '/uploads' . strrchr($_POST['photo-url'], '/');
        }
        $db_data += [
            'image'   => $db_post_image,
            'type_id' => 1,
        ];
    } elseif ($current_tab === 'video') {
        $db_post_video = $_POST['video-url'];
        $db_data += [
            'video'   => $db_post_video,
            'type_id' => 2,
        ];
    } elseif ($current_tab === 'text') {
        $db_post_text_content = $_POST['text-post'];
        $db_data += [
            'text_content' => $db_post_text_content,
            'type_id'      => 3,
        ];
    } elseif ($current_tab === 'quote') {
        $db_post_text_content = $_POST['quote-text'];
        $db_post_quote_author = $_POST['quote-author'];
        $db_data += [
            'text_content' => $db_post_text_content,
            'quote_author' => $db_post_quote_author,
            'type_id'      => 4,
        ];
    } else {
        $db_post_link = $_POST['link-url'];
        $db_data += [
            'link'    => $db_post_link,
            'type_id' => 5,
        ];
    }
    return $db_data;
}
