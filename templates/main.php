<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <?php foreach ($sort_types as $key => $sort_type): ?>
                        <li class="sorting__item <?= (($key === 'popular' && !isset($_GET['sort'])) || (isset($_GET['sort']) && $_GET['sort']) === $key) ? 'sorting__item--active' : '' ?>">
                            <a class="sorting__link <?= (($key === 'popular' && !isset($_GET['sort'])) || (isset($_GET['sort']) && $_GET['sort']) === $key) ? 'sorting__link--active' : '' ?>" href="popular.php?sort=<?= $key ?><?= (isset($_GET['type_id'])) ? '&type_id=' . $_GET['type_id'] : '' ?>">
                                <span><?= $sort_type['title'] ?></span>
                                <svg class="sorting__icon" width="10" height="12">
                                    <use xlink:href="#icon-sort"></use>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all  <?php if ($type_id === ''): ?>filters__button--active<?php endif; ?>" href="popular.php">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach ($types as $type): ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--<?= $type['class_name']; ?> <?php if ($type_id === strval($type['id'])): ?>filters__button--active<?php endif; ?> button" href="popular.php?type_id=<?= $type['id']; ?>">
                                <span class="visually-hidden">
                                <?php if ($type['title'] === 'Картинка'): ?>
                                    Фото
                                <?php else: ?>
                                    <?= $type['title']; ?>
                                <?php endif; ?>
                            </span>
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-<?= $type['class_name']; ?>"></use>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="popular__posts">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $key => $post): ?>
                    <article class="popular__post post-<?= $post['class_name']; ?>">
                        <header class="post__header">
                            <h2>
                                <a href="/post.php?id=<?= $post['id']; ?>"><?= htmlspecialchars($post['title']) ?></a>
                            </h2>
                        </header>
                        <div class="post__main">
                            <?php if ($post['class_name'] === 'quote'): ?>
                                <blockquote>
                                    <p><?= htmlspecialchars($post['text_content']) ?></p>
                                    <cite><?= htmlspecialchars($post['quote_author']) ?></cite>
                                </blockquote>
                            <?php elseif ($post['class_name'] === 'link'): ?>
                                <div class="post-link__wrapper">
                                    <a class="post-link__external" href="http://<?= htmlspecialchars($post['link']) ?>" title="Перейти по ссылке">
                                        <div class="post-link__info-wrapper">
                                            <div class="post-link__icon-wrapper">
                                                <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars(str_replace('www.',
                                                    '', $post['link'])) ?>" alt="Иконка">
                                            </div>
                                            <div class="post-link__info">
                                                <h3><?= htmlspecialchars($post['link']) ?></h3>
                                            </div>
                                        </div>
                                        <span><?= htmlspecialchars($post['link']) ?></span>
                                    </a>
                                </div>
                            <?php elseif ($post['class_name'] === 'photo'): ?>
                                <div class="post-photo__image-wrapper">
                                    <img src="img/<?= htmlspecialchars($post['image']) ?>" alt="Фото от пользователя" width="360" height="240">
                                </div>
                            <?php elseif ($post['class_name'] === 'video'): ?>
                                <div class="post-video__block">
                                    <div class="post-video__preview">
                                        <?= embed_youtube_cover(htmlspecialchars($post['video']), 360, 188); ?>
                                    </div>
                                    <a href="post-details.html" class="post-video__play-big button">
                                        <svg class="post-video__play-big-icon" width="14" height="14">
                                            <use xlink:href="#icon-video-play-big"></use>
                                        </svg>
                                        <span class="visually-hidden">Запустить проигрыватель</span>
                                    </a>
                                </div>
                            <?php elseif ($post['class_name'] === 'text'): ?>
                                <?php if (crop_text($post['text_content'])): ?>
                                    <p><?= htmlspecialchars(crop_text($post['text_content'])) . '...' ?></p>
                                    <a class="post-text__more-link" href="#">Читать далее</a>
                                <?php else: ?>
                                    <p><?= htmlspecialchars($post['text_content']) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="profile.php?id=<?= $post['user_id'] ?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="<?= $post['avatar'] ?>" alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= htmlspecialchars($post['login']) ?></b>
                                        <time class="post__time" datetime="<?= date_format(date_create($post['dt_add']),
                                            'Y-m-d'); ?>" title="<?= date_format(date_create($post['dt_add']),
                                            'd.m.Y H:i'); ?>">
                                            <?= print_date_diff($post['dt_add']); ?> назад
                                        </time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button <?= (isset($liked_post_ids_by_session_user) && in_array($post['id'],
                                        $liked_post_ids_by_session_user)) ? 'post__indicator--likes-active' : '' ?>"
                                       href="<?= $_SERVER['REQUEST_URI'] ?>&liked_post_id=<?= $post['id'] ?>"
                                       title="Лайк">
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
                                </div>
                            </div>
                        </footer>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>По данному фильтру постов не найдено.</p>
            <?php endif; ?>
        </div>
        <div class="popular__page-links">
            <?php if ($page_number > 1): ?>
                <a class="popular__page-link popular__page-link--prev button button--gray" href="popular.php?page_num=<?= ($page_number - 1) ?><?= (isset($_GET['sort'])) ? '&sort=' . $_GET['sort'] : '' ?><?= (isset($_GET['type_id'])) ? '&type_id=' . $_GET['type_id'] : '' ?>">Предыдущая
                    страница</a>
            <?php else: ?>
                <a class="popular__page-link popular__page-link--hidden popular__page-link--prev button button--gray" href="popular.php?page_num=<?= $page_number ?><?= (isset($_GET['sort'])) ? '&sort=' . $_GET['sort'] : '' ?><?= (isset($_GET['type_id'])) ? '&type_id=' . $_GET['type_id'] : '' ?>">Предыдущая
                    страница</a>
            <?php endif; ?>
            <?php if ($page_number < $max_page): ?>
                <a class="popular__page-link popular__page-link--next button button--gray" href="popular.php?page_num=<?= ($page_number + 1) ?><?= (isset($_GET['sort'])) ? '&sort=' . $_GET['sort'] : '' ?><?= (isset($_GET['type_id'])) ? '&type_id=' . $_GET['type_id'] : '' ?>">Следующая
                    страница</a>
            <?php endif; ?>
        </div>
    </div>
</section>
