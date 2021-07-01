<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= $post['title'] ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-<?= $post['class_name'] ?>">
                <div class="post-details__main-block post post--details">

                    <?php if ($post['class_name'] === 'quote'): ?>
                        <div class="post__main">
                            <blockquote>
                                <p><?= htmlspecialchars($post['text_content']) ?></p>
                                <cite><?= htmlspecialchars($post['quote_author']) ?></cite>
                            </blockquote>
                        </div>

                    <?php elseif ($post['class_name'] === 'link'): ?>
                        <div class="post__main">
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="<?= htmlspecialchars($post['link']) ?>" title="Перейти по ссылке">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars(str_replace('www.',
                                            '', $post['link'])) ?>" alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                                        <span><?= htmlspecialchars($post['link']) ?></span>
                                    </div>
                                    <svg class="post-link__arrow" width="11" height="16">
                                        <use xlink:href="#icon-arrow-right-ad"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>

                    <?php elseif ($post['class_name'] === 'photo'): ?>
                        <div class="post__main">
                            <div class="post-details__image-wrapper post-photo__image-wrapper">
                                <img src="<?= htmlspecialchars($post['image']) ?>" alt="Фото от пользователя" width="760" height="507">
                            </div>
                        </div>

                    <?php elseif ($post['class_name'] === 'video'): ?>
                        <div class="post__main">
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?= embed_youtube_cover(htmlspecialchars($post['video']), 760, 507); ?>
                                </div>
                                <div class="post-video__control">
                                    <button class="post-video__play post-video__play--paused button button--video" type="button">
                                        <span class="visually-hidden">Запустить видео</span></button>
                                    <div class="post-video__scale-wrapper">
                                        <div class="post-video__scale">
                                            <div class="post-video__bar">
                                                <div class="post-video__toggle"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button">
                                        <span class="visually-hidden">Полноэкранный режим</span></button>
                                </div>
                                <button class="post-video__play-big button" type="button">
                                    <svg class="post-video__play-big-icon" width="27" height="28">
                                        <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                    <span class="visually-hidden">Запустить проигрыватель</span>
                                </button>
                            </div>
                        </div>

                    <?php elseif ($post['class_name'] === 'text'): ?>
                        <div class="post__main">
                            <p><?= htmlspecialchars($post['text_content']) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button <?= (in_array($post['id'],
                                $liked_post_ids_by_session_user)) ? 'post__indicator--likes-active' : '' ?>"
                               href="post.php?id=<?= $id ?>&liked"
                               title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?= count_likes_of_post($con, $id) ?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#last_comment" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= count_comments_of_post($con, $id) ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button <?= (isset($reposted_post_ids_by_session_user) && in_array($post['id'],
                                $reposted_post_ids_by_session_user)) ? 'post__indicator--repost-active' : '' ?>"
                               href="post.php?id=<?= $id ?>&reposted"
                               title="Репост">
                                <svg class="post__indicator-icon <?= (isset($reposted_post_ids_by_session_user) && in_array($post['id'],
                                    $reposted_post_ids_by_session_user)) ? 'post__indicator-icon--repost-active' : '' ?>" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?= count_reposts_of_post($con, $id) ?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view"><?= $views_count ?> <?= get_noun_plural_form($views_count, 'просмотр',
                                'просмотра', 'просмотров') ?></span>
                    </div>
                    <?php if ($post_tags): ?>
                        <ul class="post__tags">
                            <?php foreach ($post_tags as $tag): ?>
                                <li>
                                    <a href="search.php?q=<?= $tag['name'] ?>&type=tag">#<?= $tag['name'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="comments">
                        <form class="comments__form form" action="post.php?id=<?= $id ?>" method="post">
                            <input type="hidden" name="id" value="<?= $id; ?>">
                            <div class="comments__my-avatar">
                                <img class="comments__picture" src="<?= $_SESSION['user']['avatar'] ?>" alt="Аватар пользователя">
                            </div>
                            <div class="form__input-section <?php if (!empty($errors[$form['name']])): ?>form__input-section--error<?php endif; ?>">
                                <textarea class="comments__textarea form__textarea form__input"
                                          name="comment"
                                          placeholder="Ваш комментарий"
                                          <?php if ($count_of_shown_post_comments === 0): ?>id="last_comment"<?php endif; ?>><?= $_POST['comment'] ?? '' ?></textarea>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <?php if (!empty($errors[$form['name']])): ?>
                                    <button class="form__error-button button" type="button">!</button>
                                    <div class="form__error-text">
                                        <h3 class="form__error-title">Ошибка валидации</h3>
                                        <p class="form__error-desc"><?= $errors[$form['name']]['comment']; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button class="comments__submit button button--green" type="submit">Отправить</button>
                        </form>


                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                                <?php for ($i = 0; $i < $count_of_shown_post_comments; $i++): ?>
                                    <li class="comments__item user" <?php if ($i === $count_of_shown_post_comments - 1): ?>id="last_comment"<?php endif; ?>>
                                        <div class="comments__avatar">
                                            <a class="user__avatar-link" href="profile.php?id=<?= $post_comments[$i]['id'] ?>">
                                                <img class="comments__picture" src="<?= $post_comments[$i]['avatar'] ?>" alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="profile.php?id=<?= $post_comments[$i]['id'] ?>">
                                                    <span><?= $post_comments[$i]['login'] ?></span>
                                                </a>
                                                <time class="comments__time" datetime="<?= date_format(date_create($post_comments[$i]['publish_time']),
                                                    'Y-m-d'); ?>">
                                                    <?= print_date_diff($post_comments[$i]['publish_time']); ?> назад
                                                </time>
                                            </div>
                                            <p class="comments__text">
                                                <?= $post_comments[$i]['content'] ?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endfor; ?>
                            </ul>

                            <?php if ($count_of_post_comments > NUMBER_OF_SHOWN_POST_COMMENTS): ?>
                                <a class="comments__more-link" href="#">
                                    <span>Показать все комментарии</span>
                                    <sup class="comments__amount"><?= $count_of_post_comments ?></sup>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="profile.php?id=<?= $post['user_id'] ?>">
                                <img class="post-details__picture user__picture" src="../<?= $post['avatar'] ?>" alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="profile.php?id=<?= $post['user_id'] ?>">
                                <span><?= $post['login'] ?></span>
                            </a>
                            <time class="post-details__time user__time" datetime="<?= date_format(date_create($author['dt_add']),
                                'Y-m-d'); ?>"><?= print_date_diff($author['dt_add']); ?>
                                на сайте
                            </time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount"><?= $amount_of_user_followers ?></span>
                            <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($amount_of_user_followers,
                                    'подписчик', 'подписчика', 'подписчиков') ?></span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount"><?= $amount_of_user_posts ?></span>
                            <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form($amount_of_user_posts,
                                    'публикация', 'публикации', 'публикаций') ?></span>
                        </p>
                    </div>

                    <?php if (!$self_page): ?>
                        <div class="post-details__user-buttons user__buttons">
                            <?php if ($subscribe): ?>
                                <a class="user__button user__button--subscription button button--quartz" href="profile.php?id=<?= $post['user_id'] ?>&unsubscribed">
                                    Отписаться
                                </a>
                                <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                            <?php else: ?>
                                <a class="user__button user__button--subscription button button--main" href="profile.php?id=<?= $post['user_id'] ?>&subscribed">
                                    Подписаться
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </section>
    </div>
</main>
