<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">

            <ul class="messages__contacts-list tabs__list">
                <?php if ((isset($_GET['id'])) && (!in_array(intval($_GET["id"]), $existed_interlocutors_ids))) : ?>
                    <li class="messages__contacts-item">
                        <a class="messages__contacts-tab tabs__item messages__contacts-tab--active tabs__item--active"
                           href="message.php?id=<?= $id ?? '' ?>">
                            <div class="messages__avatar-wrapper">
                                <img class="messages__avatar" src="<?= $current_interlocutor['avatar'] ??
                                '' ?>" alt="Аватар пользователя">
                            </div>
                            <div class="messages__info">
                                <span class="messages__contact-name"><?= $current_interlocutor['login'] ?></span>
                                <div class="messages__preview">
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endif; ?>
                <?php foreach ($interlocutors as $interlocutor) : ?>
                    <li class="messages__contacts-item
                    <?php if ($interlocutor['unread_messages'] > 0) : ?>
                    messages__contacts-item--new
                    <?php endif; ?>">
                        <a class="messages__contacts-tab tabs__item <?= (isset($id) && $interlocutor['user_id'] === $id)
                            ? "messages__contacts-tab--active tabs__item--active"
                            : "" ?>" href="message.php?id=<?= $interlocutor['user_id'] ?? '' ?>">
                            <div class="messages__avatar-wrapper">
                                <img class="messages__avatar" src="<?= $interlocutor['avatar'] ??
                                '' ?>" alt="Аватар пользователя">
                                <?php if ($interlocutor['unread_messages'] > 0) : ?>
                                    <i class="messages__indicator"><?= $interlocutor['unread_messages'] ?? '' ?></i>
                                <?php endif; ?>
                            </div>
                            <div class="messages__info">
                                <span class="messages__contact-name"><?= $interlocutor['login'] ?? '' ?></span>
                                <div class="messages__preview">
                                    <p class="messages__preview-text">
                                        <?= $interlocutor['is_last_message_mine'] ? 'Вы: ' : '' ?>
                                        <?= $interlocutor['last_message'] ?? '' ?>
                                    </p>
                                    <time class="messages__preview-time"
                                          datetime="
                                          <?= date_format(
                                              date_create($interlocutor['last_message_time'] ?? ''),
                                              'Y-m-d'
                                          ); ?>">
                                        <?= print_last_message_date($interlocutor['last_message_time']) ?>
                                    </time>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="messages__chat">
            <?php if (isset($_GET['id'])) : ?>
                <div class="messages__chat-wrapper">
                    <ul class="messages__list tabs__content tabs__content--active">
                        <?php foreach ($messages as $message) : ?>
                            <?php if ($message['user_id'] === $id) : ?>
                                <li class="messages__item <?= ($message['sender_id']
                                    === intval($_SESSION['user']['id'])) ? 'messages__item--my' : '' ?>">
                                    <div class="messages__info-wrapper">
                                        <div class="messages__item-avatar">
                                            <a class="messages__author-link"
                                               href="profile.php?id=<?= ($message['sender_id'] ===
                                                   intval($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : $id ?>">
                                                <img class="messages__avatar" src="
                                                <?= ($message['sender_id'] === intval($_SESSION['user']['id']))
                                                    ? $_SESSION['user']['avatar'] : $message['avatar'] ?>"
                                                     alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="messages__item-info">
                                            <a class="messages__author"
                                               href="profile.php?id=<?= ($message['sender_id'] ===
                                                   intval($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : $id ?>">
                                                <?= ($message['sender_id'] === intval($_SESSION['user']['id']))
                                                    ? $_SESSION['user']['login'] : $message['login'] ?>
                                            </a>
                                            <time class="messages__time" datetime="
                                            <?= date_format(date_create($message['dt_add'] ?? ''), 'Y-m-d'); ?>">
                                                <?= print_date_diff($message['dt_add'] ?? ''); ?> назад
                                            </time>
                                        </div>
                                    </div>
                                    <p class="messages__text">
                                        <?= $message['content'] ?? '' ?>
                                    </p>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="comments">
                    <form class="comments__form form" action="message.php?id=<?= $id ?? '' ?>" method="post">
                        <input type="hidden" name="id" value="<?= $id; ?>">
                        <div class="comments__my-avatar">
                            <img class="comments__picture"
                                 src="<?= $_SESSION['user']['avatar'] ?>" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section
                        <?php if (!empty($errors[$form['name']])) : ?>
                        form__input-section--error
                        <?php endif; ?>">
                                <textarea class="comments__textarea form__textarea form__input"
                                          name="message"
                                          placeholder="Ваше сообщение"><?= $_POST['message'] ?? '' ?></textarea>
                            <label class="visually-hidden">Ваше сообщение</label>
                            <?php if (!empty($errors[$form['name']])) : ?>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc"><?= $errors[$form['name']]['message']; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
