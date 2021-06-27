<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">

                    <?php foreach ($current_tab_posts as $post): ?>
                        <article class="search__post post post-<?= $post['class_name']; ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="profile.php?id=<?= $post['user_id'] ?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="<?= $post['avatar'] ?>" alt="Аватар пользователя" width="60" height="60">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= htmlspecialchars($post['login']) ?></b>
                                        <span class="post__time"><?= print_date_diff($post['dt_add']); ?> назад</span>
                                    </div>
                                </a>
                            </header>
                            <div class="post__main">

                                <?php if ($post['class_name'] === 'photo'): ?>
                                    <h2><a href="/post.php?id=<?= $post['id']; ?>"><?= $post['title']; ?></a></h2>
                                    <div class="post-photo__image-wrapper">
                                        <img src="<?= $post['image']; ?>" alt="Фото от пользователя" width="760" height="396">
                                    </div>

                                <?php elseif ($post['class_name'] === 'video'): ?>
                                    <div class="post-video__block">
                                        <div class="post-video__preview">
                                            <?= embed_youtube_cover(htmlspecialchars($post['video']), 760, 396); ?>
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

                                <?php elseif ($post['class_name'] === 'text'): ?>
                                    <h2><a href="/post.php?id=<?= $post['id']; ?>"><?= $post['title']; ?></a></h2>
                                    <?php if (crop_text($post['text_content'], 1000)): ?>
                                        <p><?= htmlspecialchars(crop_text($post['text_content'], 1000)) . '...' ?></p>
                                        <a class="post-text__more-link" href="/post.php?id=<?= $post['id']; ?>">Читать
                                            далее</a>
                                    <?php else: ?>
                                        <p><?= htmlspecialchars($post['text_content']) ?></p>
                                    <?php endif; ?>

                                <?php elseif ($post['class_name'] === 'quote'): ?>
                                    <blockquote>
                                        <p><?= htmlspecialchars($post['text_content']) ?></p>
                                        <cite><?= htmlspecialchars($post['quote_author']) ?></cite>
                                    </blockquote>

                                <?php elseif ($post['class_name'] === 'link'): ?>
                                    <div class="post-link__wrapper">
                                        <a class="post-link__external" href="<?= htmlspecialchars($post['link']) ?>" title="Перейти по ссылке">
                                            <div class="post-link__icon-wrapper">
                                                <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars(str_replace('www.', '', $post['link'])) ?>" alt="Иконка">
                                            </div>
                                            <div class="post-link__info">
                                                <h3><?= $post['title'] ?></h3>
                                                <span><?= htmlspecialchars($post['link']) ?></span>
                                            </div>
                                            <svg class="post-link__arrow" width="11" height="16">
                                                <use xlink:href="#icon-arrow-right-ad"></use>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button <?= (in_array($post['id'],
                                        $liked_post_ids_by_session_user)) ? 'post__indicator--likes-active' : '' ?>" href="feed.php?liked_post_id=<?= $post['id'] ?>" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= count_likes_of_post($con, $post['id']) ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="post.php?id=<?= $post['id'] ?>#last_comment" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= count_comments_of_post($con, $post['id']) ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                    <a class="post__indicator post__indicator--repost button <?= (in_array($post['id'],
                                        $reposted_post_ids_by_session_user)) ? 'post__indicator--repost-active' : '' ?>" href="feed.php?reposted_post_id=<?= $post['id'] ?>" title="Репост">
                                        <svg class="post__indicator-icon <?= (in_array($post['id'],
                                            $reposted_post_ids_by_session_user)) ? 'post__indicator-icon--repost-active' : '' ?>" width="19" height="17">
                                            <use xlink:href="#icon-repost"></use>
                                        </svg>
                                        <span><?= count_reposts_of_post($con, $post['id']) ?></span>
                                        <span class="visually-hidden">количество репостов</span>
                                    </a>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>

                </div>
            </div>
            <ul class="feed__filters filters">
                <li class="feed__filters-item filters__item">
                    <a class="filters__button filters__button--active" href="feed.php">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($tabs as $type => $tab_name): ?>
                    <li class="feed__filters-item filters__item">
                        <a class="filters__button filters__button--<?= $type ?> <?= ($current_tab === $type) ? 'filters__button--active' : ''?> button" href="feed.php?type=<?= $type ?>">
                            <span class="visually-hidden"><?= $tab_name ?></span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-<?= $type ?>"></use>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
