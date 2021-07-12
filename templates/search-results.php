<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= $result_text ?? '' ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">

                    <?php foreach ($posts as $post) : ?>
                        <article class="search__post post post-<?= $post['class_name'] ?? '' ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="profile.php?id=<?= $post['user_id'] ??
                                '' ?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="<?= $post['avatar'] ??
                                        '' ?>" alt="Аватар пользователя" width="60" height="60">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= $post['login'] ?? '' ?></b>
                                        <span class="post__time"><?= print_date_diff($post['dt_add'] ??
                                                '') ?> назад</span>
                                    </div>
                                </a>
                            </header>
                            <div class="post__main">

                                <?php if ($post['class_name'] === 'photo') : ?>
                                    <h2><a href="post.php?id=<?= $post['id'] ?? '' ?>"><?= $post['title'] ?? '' ?></a>
                                    </h2>
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
                                                <span class="visually-hidden">Запустить видео</span></button>
                                            <div class="post-video__scale-wrapper">
                                                <div class="post-video__scale">
                                                    <div class="post-video__bar">
                                                        <div class="post-video__toggle"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="post-video__fullscreen post-video__fullscreen--inactive
                                            button button--video" type="button">
                                                <span class="visually-hidden">Полноэкранный режим</span></button>
                                        </div>
                                        <button class="post-video__play-big button" type="button">
                                            <svg class="post-video__play-big-icon" width="27" height="28">
                                                <use xlink:href="#icon-video-play-big"></use>
                                            </svg>
                                            <span class="visually-hidden">Запустить проигрыватель</span>
                                        </button>
                                    </div>

                                <?php elseif ($post['class_name'] === 'text') : ?>
                                    <h2><a href="post.php?id=<?= $post['id'] ?? '' ?>"><?= $post['title'] ?? '' ?></a>
                                    </h2>
                                    <?php if (crop_text($post['text_content'], 1000)) : ?>
                                        <p><?= htmlspecialchars(crop_text($post['text_content'], 1000)) . '...' ?></p>
                                        <a class="post-text__more-link" href="post.php?id=<?= $post['id'] ?? '' ?>">
                                            Читать
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
                                        <a class="post-link__external" href="<?= htmlspecialchars($post['link']) ??
                                        '' ?>" title="Перейти по ссылке">
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
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button
                                    <?= (isset($liked_post_ids_by_session_user)
                                        && in_array($post['id'], $liked_post_ids_by_session_user))
                                        ? 'post__indicator--likes-active' : '' ?>"
                                       href="search.php?liked_post_id=<?= $post['id'] ?? '' ?>"
                                       title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active"
                                             width="20" height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= count_likes_of_post($con, $post['id']) ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button"
                                       href="post.php?id=<?= $post['id'] ?? '' ?>#last_comment"
                                       title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= count_comments_of_post($con, $post['id']) ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
