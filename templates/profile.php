<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="<?= $user['avatar'] ??
                        '' ?>" alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= $user['login'] ?? '' ?></span>
                        <time class="profile__user-time user__time" datetime="
                        <?= date_format(date_create($user['dt_add']
                            ?? ''), 'Y-m-d'); ?>"><?= print_date_diff($user['dt_add'] ?? '') ?>
                            на сайте
                        </time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= $amount_of_user_posts ?? '0' ?></span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form(
                                $amount_of_user_posts,
                                'публикация',
                                'публикации',
                                'публикаций'
                            ) ?></span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= $amount_of_user_followers ?? '0' ?></span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form(
                                $amount_of_user_followers,
                                'подписчик',
                                'подписчика',
                                'подписчиков'
                            ) ?></span>
                    </p>
                </div>

                <?php if (!$self_page) : ?>
                    <div class="profile__user-buttons user__buttons">
                        <?php if ($subscribe) : ?>
                            <a class="profile__user-button user__button user__button--subscription button
                            button--quartz" href="profile.php?id=<?= $user['id']
                            ?? '' ?>&unsubscribed">
                                Отписаться
                            </a>
                            <a class="profile__user-button user__button user__button--writing button
                            button--green" href="message.php?id=<?= $user['id']
                            ?? '' ?>">Сообщение</a>
                        <?php else : ?>
                            <a class="profile__user-button user__button user__button--subscription
                            button button--main" href="profile.php?id=<?= $user['id']
                            ?? '' ?>&subscribed">
                                Подписаться
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <?php foreach ($tabs as $type => $name) : ?>
                            <li class="profile__tabs-item filters__item">
                                <a class="profile__tabs-link filters__button
                                <?php if ($type === $current_tab) : ?>
                                filters__button--active tabs__item--active
                                <?php endif; ?> tabs__item button" href="/profile.php?id=<?= $user['id']
                                ?? '' ?>&tab=<?= $type ?? '' ?>"><?= $name ?? '' ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="profile__tab-content">
                    <?php if (isset($_GET['tab']) && $_GET['tab'] === 'likes') : ?>
                        <section class="profile__likes tabs__content tabs__content--active">
                            <h2 class="visually-hidden">Лайки</h2>
                            <?php if (!empty($liked_posts_of_user)) : ?>
                                <ul class="profile__likes-list">
                                    <?php foreach ($liked_posts_of_user as $post) : ?>
                                        <li class="post-mini post-mini--<?= $post['class_name'] ?? '' ?> post user">
                                            <div class="post-mini__user-info user__info">
                                                <div class="post-mini__avatar user__avatar">
                                                    <a class="user__avatar-link" href="profile.php?id=
                                                    <?= $post['like_user_id']
                                                    ?? '' ?>">
                                                        <img class="post-mini__picture user__picture" src="
                                                        <?= $post['avatar']
                                                        ?? '' ?>" alt="Аватар пользователя">
                                                    </a>
                                                </div>
                                                <div class="post-mini__name-wrapper user__name-wrapper">
                                                    <a class="post-mini__name user__name" href="profile.php?id=
                                                    <?= $post['like_user_id']
                                                    ?? '' ?>">
                                                        <span><?= $post['login'] ?? '' ?></span>
                                                    </a>
                                                    <div class="post-mini__action">
                                                        <span class="post-mini__activity user__additional">
                                                            Лайкнул вашу публикацию
                                                        </span>
                                                        <time class="post-mini__time user__additional"
                                                              datetime="
                                                              <?= date_format(
                                                                  date_create($post['like_dt_add'] ?? ''),
                                                                  'Y-m-d'
                                                              ); ?>">
                                                            <?= print_date_diff($post['like_dt_add'] ?? ''); ?>
                                                            назад
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="post-mini__preview">
                                                <a class="post-mini__link" href="post.php?id=<?= $post['id'] ??
                                                '' ?>" title="Перейти на публикацию">
                                                    <?php if ($post['class_name'] === 'photo') : ?>
                                                        <div class="post-mini__image-wrapper">
                                                            <img class="post-mini__image" src="<?= $post['image'] ??
                                                            '' ?>" width="109" height="109" alt="Превью публикации">
                                                        </div>
                                                        <span class="visually-hidden">Фото</span>
                                                    <?php elseif ($post['class_name'] === 'text') : ?>
                                                        <span class="visually-hidden">Текст</span>
                                                        <svg class="post-mini__preview-icon" width="20" height="21">
                                                            <use xlink:href="#icon-filter-text"></use>
                                                        </svg>
                                                    <?php elseif ($post['class_name'] === 'video') : ?>
                                                        <div class="post-mini__image-wrapper">
                                                            <?= embed_youtube_cover($post['video'] ?? '', 109, 109); ?>
                                                            <span class="post-mini__play-big">
                                                            <svg class="post-mini__play-big-icon"
                                                                 width="12" height="13">
                                                            <use xlink:href="#icon-video-play-big"></use>
                                                            </svg>
                                                        </span>
                                                        </div>
                                                        <span class="visually-hidden">Видео</span>
                                                    <?php elseif ($post['class_name'] === 'quote') : ?>
                                                        <span class="visually-hidden">Цитата</span>
                                                        <svg class="post-mini__preview-icon" width="21" height="18">
                                                            <use xlink:href="#icon-filter-link"></use>
                                                        </svg>
                                                    <?php elseif ($post['class_name'] === 'link') : ?>
                                                        <span class="visually-hidden">Ссылка</span>
                                                        <svg class="post-mini__preview-icon" width="21" height="18">
                                                            <use xlink:href="#icon-filter-link"></use>
                                                        </svg>
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </section>
                    <?php elseif (isset($_GET['tab']) && $_GET['tab'] === 'follows') : ?>
                        <section class="profile__subscriptions tabs__content tabs__content--active">
                            <h2 class="visually-hidden">Подписки</h2>
                            <?php if (!empty($followers_of_user)) : ?>
                                <ul class="profile__subscriptions-list">
                                    <?php foreach ($followers_of_user as $follower) : ?>
                                        <li class="post-mini post-mini--photo post user">
                                            <div class="post-mini__user-info user__info">
                                                <div class="post-mini__avatar user__avatar">
                                                    <a class="user__avatar-link"
                                                       href="profile.php?id=<?= $follower['id'] ?? '' ?>">
                                                        <img class="post-mini__picture user__picture"
                                                             src="<?= $follower['avatar'] ?? '' ?>"
                                                             alt="Аватар пользователя">
                                                    </a>
                                                </div>
                                                <div class="post-mini__name-wrapper user__name-wrapper">
                                                    <a class="post-mini__name user__name"
                                                       href="profile.php?id=<?= $follower['id'] ?? '' ?>">
                                                        <span><?= $follower['login'] ?? '' ?></span>
                                                    </a>
                                                    <time class="post-mini__time user__additional"
                                                          datetime="
                                                          <?= date_format(
                                                              date_create($follower['dt_add'] ?? ''),
                                                              'Y-m-d'
                                                          ); ?>">
                                                        <?= print_date_diff($follower['dt_add'] ?? ''); ?>
                                                        на сайте
                                                    </time>
                                                </div>
                                            </div>
                                            <div class="post-mini__rating user__rating">
                                                <p class="post-mini__rating-item user__rating-item
                                                user__rating-item--publications">
                                                    <span class="post-mini__rating-amount user__rating-amount">
                                                        <?= $follower['amount_of_posts']
                                                        ?? '' ?></span>
                                                    <span class="post-mini__rating-text user__rating-text">
                                                        <?= get_noun_plural_form(
                                                            $follower['amount_of_posts']
                                                            ?? '',
                                                            'публикация',
                                                            'публикации',
                                                            'публикаций'
                                                        ) ?></span>
                                                </p>
                                                <p class="post-mini__rating-item user__rating-item
                                                user__rating-item--subscribers">
                                                    <span class="post-mini__rating-amount user__rating-amount">
                                                        <?= $follower['amount_of_followers']
                                                        ?? '' ?></span>
                                                    <span class="post-mini__rating-text user__rating-text">
                                                        <?= get_noun_plural_form(
                                                            $follower['amount_of_followers']
                                                            ?? '',
                                                            'подписчик',
                                                            'подписчика',
                                                            'подписчиков'
                                                        ) ?></span>
                                                </p>
                                            </div>
                                            <div class="post-mini__user-buttons user__buttons">
                                                <?php if ($follower['id'] !== intval($_SESSION['user']['id'])) : ?>
                                                    <?php if ($follower['subscribed_by_session_user']) : ?>
                                                        <a class="post-mini__user-button user__button
                                                        user__button--subscription button button--quartz"
                                                           href="profile.php?id=
                                                           <?= $follower['id'] ?? '' ?>&unsubscribed">
                                                            Отписаться
                                                        </a>
                                                    <?php else : ?>
                                                        <a class="post-mini__user-button user__button
                                                        user__button--subscription button button--main"
                                                           href="profile.php?id=
                                                           <?= $follower['id'] ?? '' ?>&subscribed">
                                                            Подписаться
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <a class="post-mini__user-button user__button
                                                    user__button--subscription button button--main"
                                                       href="profile.php?id=<?= $follower['id'] ?? '' ?>">
                                                        На свою страницу
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </section>
                    <?php else : ?>
                        <section class="profile__posts tabs__content tabs__content--active">
                            <h2 class="visually-hidden">Публикации</h2>
                            <?php if (!empty($user_posts)) : ?>
                                <?php foreach ($user_posts as $post) : ?>
                                    <article class="profile__post post post-<?= $post['class_name'] ?? '' ?>">
                                        <header class="post__header">
                                            <h2>
                                                <a href="post.php?id=<?= $post['id'] ?? '' ?>"><?= $post['title'] ??
                                                    '' ?></a>
                                            </h2>
                                        </header>
                                        <div class="post__main">
                                            <?php if ($post['class_name'] === 'photo') : ?>
                                                <div class="post-photo__image-wrapper">
                                                    <img src="<?= $post['image'] ??
                                                    '' ?>" alt="Фото от пользователя" width="760" height="396">
                                                </div>
                                            <?php elseif ($post['class_name'] === 'video') : ?>
                                                <div class="post-video__block">
                                                    <div class="post-video__preview">
                                                        <?= embed_youtube_cover($post['video'] ?? '', 760, 396); ?>
                                                    </div>
                                                    <div class="post-video__control">
                                                        <button class="post-video__play post-video__play--paused
                                                        button button--video" type="button">
                                                            <span class="visually-hidden">Запустить видео</span>
                                                        </button>
                                                        <div class="post-video__scale-wrapper">
                                                            <div class="post-video__scale">
                                                                <div class="post-video__bar">
                                                                    <div class="post-video__toggle"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="post-video__fullscreen
                                                        post-video__fullscreen--inactive button button--video"
                                                                type="button">
                                                            <span class="visually-hidden">Полноэкранный режим</span>
                                                        </button>
                                                    </div>
                                                    <button class="post-video__play-big button" type="button">
                                                        <svg class="post-video__play-big-icon" width="27" height="28">
                                                            <use xlink:href="#icon-video-play-big"></use>
                                                        </svg>
                                                        <span class="visually-hidden">Запустить проигрыватель</span>
                                                    </button>
                                                </div>
                                            <?php elseif ($post['class_name'] === 'text') : ?>
                                                <?php if (crop_text($post['text_content'], 1000)) : ?>
                                                    <p><?= htmlspecialchars(crop_text($post['text_content'], 1000))
                                                        . '...' ?></p>
                                                    <a class="post-text__more-link" href="/post.php?id=<?= $post['id']
                                                    ?? '' ?>">Читать
                                                        далее</a>
                                                <?php else : ?>
                                                    <p><?= htmlspecialchars($post['text_content']) ?? '' ?></p>
                                                <?php endif; ?>
                                            <?php elseif ($post['class_name'] === 'quote') : ?>
                                                <blockquote>
                                                    <p><?= htmlspecialchars($post['text_content']) ?? '' ?></p>
                                                    <cite><?= htmlspecialchars($post['quote_author']) ?? '' ?></cite>
                                                </blockquote>
                                            <?php elseif ($post['class_name'] === 'link') : ?>
                                                <div class="post-link__wrapper">
                                                    <a class="post-link__external"
                                                       href="<?= htmlspecialchars($post['link']) ?? '' ?>"
                                                       title="Перейти по ссылке">
                                                        <div class="post-link__icon-wrapper">
                                                            <img src="https://www.google.com/s2/favicons?domain=
                                                            <?= htmlspecialchars(
                                                                str_replace(
                                                                    'www.',
                                                                    '',
                                                                    $post['link'] ?? ''
                                                                )
                                                            ) ?>" alt="Иконка">
                                                        </div>
                                                        <div class="post-link__info">
                                                            <h3><?= $post['title'] ?? '' ?></h3>
                                                            <span><?= htmlspecialchars($post['link']) ?? '' ?></span>
                                                        </div>
                                                        <svg class="post-link__arrow" width="11" height="16">
                                                            <use xlink:href="#icon-arrow-right-ad"></use>
                                                        </svg>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <footer class="post__footer">
                                            <div class="post__indicators">
                                                <div class="post__buttons">
                                                    <a class="post__indicator post__indicator--likes button
                                                    <?= (isset($liked_post_ids_by_session_user)
                                                        && in_array($post['id'], $liked_post_ids_by_session_user))
                                                        ? 'post__indicator--likes-active' : '' ?>"
                                                       href="profile.php?id=<?= $user['id'] ?? '' ?>
                                                       &liked_post_id=<?= $post['id'] ?? '' ?>"
                                                       title="Лайк">
                                                        <svg class="post__indicator-icon" width="20" height="17">
                                                            <use xlink:href="#icon-heart"></use>
                                                        </svg>
                                                        <svg class="post__indicator-icon
                                                        post__indicator-icon--like-active" width="20" height="17">
                                                            <use xlink:href="#icon-heart-active"></use>
                                                        </svg>
                                                        <span><?= count_likes_of_post($con, $post['id']) ?></span>
                                                        <span class="visually-hidden">количество лайков</span>
                                                    </a>
                                                    <a class="post__indicator post__indicator--repost button
                                                    <?= (isset($reposted_post_ids_by_session_user)
                                                        && in_array($post['id'], $reposted_post_ids_by_session_user))
                                                        ? 'post__indicator--repost-active' : '' ?>"
                                                       href="profile.php?id=<?= $user['id'] ?? '' ?>
                                                       &reposted_post_id=<?= $post['id'] ?? '' ?>" title="Репост">
                                                        <svg class="post__indicator-icon
                                                        <?= (isset($reposted_post_ids_by_session_user)
                                                            && in_array(
                                                                $post['id'],
                                                                $reposted_post_ids_by_session_user
                                                            ))
                                                            ? 'post__indicator-icon--repost-active'
                                                            : '' ?>" width="19" height="17">
                                                            <use xlink:href="#icon-repost"></use>
                                                        </svg>
                                                        <span><?= count_reposts_of_post($con, $post['id']) ?></span>
                                                        <span class="visually-hidden">количество репостов</span>
                                                    </a>
                                                </div>
                                                <time class="post__time" datetime="
                                                <?= date_format(date_create($post['dt_add']
                                                    ?? ''), 'Y-m-d'); ?>"><?= print_date_diff($post['dt_add'] ?? ''); ?>
                                                    назад
                                                </time>
                                            </div>
                                            <?php if ($post['post_tags']) : ?>
                                                <ul class="post__tags">
                                                    <?php foreach ($post['post_tags'] as $tag) : ?>
                                                        <li>
                                                            <a href="search.php?q=<?= $tag['name'] ??
                                                            '' ?>&type=tag">#<?= $tag['name'] ?? '' ?></a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </footer>
                                        <div class="comments">
                                            <a class="comments__button button" href="post.php?id=<?= $post['id'] ??
                                            '' ?>#last_comment">Показать комментарии</a>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
