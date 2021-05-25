<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="<?= $user['avatar'] ?>" alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= $user['login'] ?></span>
                        <time class="profile__user-time user__time" datetime="<?= date_format(date_create($user['dt_add']), 'Y-m-d'); ?>"><?= print_date_diff($user['dt_add']); ?>
                            на сайте
                        </time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= $amountOfUserPosts ?></span>
                        <span class="profile__rating-text user__rating-text"><?= get_noun_plural_form($amountOfUserPosts, 'публикация', 'публикации', 'публикаций') ?></span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= $amountOfUserFollowers ?></span>
                        <span class="profile__rating-text user__rating-text"><?= get_noun_plural_form($amountOfUserFollowers, 'подписчик', 'подписчика', 'подписчиков') ?></span>
                    </p>
                </div>

                <?php if (!$selfPage): ?>
                    <div class="profile__user-buttons user__buttons">
                        <?php if ($subscribe): ?>
                            <a class="profile__user-button user__button user__button--subscription button button--main" href="profile.php?id=<?= $user['id'] ?>&unsubscribed">
                                Отписаться
                            </a>
                            <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
                        <?php else: ?>
                            <a class="profile__user-button user__button user__button--subscription button button--main" href="profile.php?id=<?= $user['id'] ?>&subscribed">
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
                        <?php foreach ($tabs as $type => $name): ?>
                            <li class="profile__tabs-item filters__item">
                                <a class="profile__tabs-link filters__button <?php if ($type === $currentTab): ?>filters__button--active tabs__item--active<?php endif; ?> tabs__item button" href="/profile.php?id=<?= $user['id'] ?>&tab=<?= $type ?>"><?= $name ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="profile__tab-content">

                    <?php if (isset($_GET['tab']) && $_GET['tab'] === 'likes'): ?>
                        <section class="profile__likes tabs__content tabs__content--active">
                            <h2 class="visually-hidden">Лайки</h2>
                            <ul class="profile__likes-list">
                                <?php foreach ($likedPostsOfUser as $post): ?>
                                    <li class="post-mini post-mini--<?= $post['class_name'] ?> post user">
                                        <div class="post-mini__user-info user__info">
                                            <div class="post-mini__avatar user__avatar">
                                                <a class="user__avatar-link" href="profile.php?id=<?= $post['like_user_id'] ?>">
                                                    <img class="post-mini__picture user__picture" src="<?= $post['avatar'] ?>" alt="Аватар пользователя">
                                                </a>
                                            </div>
                                            <div class="post-mini__name-wrapper user__name-wrapper">
                                                <a class="post-mini__name user__name" href="profile.php?id=<?= $post['like_user_id'] ?>">
                                                    <span><?= $post['login'] ?></span>
                                                </a>
                                                <div class="post-mini__action">
                                                    <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                                    <time class="post-mini__time user__additional" datetime="<?= date_format(date_create($post['like_dt_add']), 'Y-m-d'); ?>">
                                                        <?= print_date_diff($post['like_dt_add']); ?> назад
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="post-mini__preview">
                                            <a class="post-mini__link" href="post.php?id=<?= $post['id'] ?>" title="Перейти на публикацию">
                                                <?php if ($post['class_name'] === 'photo'): ?>
                                                    <div class="post-mini__image-wrapper">
                                                        <img class="post-mini__image" src="<?= $post['image'] ?>" width="109" height="109" alt="Превью публикации">
                                                    </div>
                                                    <span class="visually-hidden">Фото</span>
                                                <?php elseif ($post['class_name'] === 'text'): ?>
                                                    <span class="visually-hidden">Текст</span>
                                                    <svg class="post-mini__preview-icon" width="20" height="21">
                                                        <use xlink:href="#icon-filter-text"></use>
                                                    </svg>
                                                <?php elseif ($post['class_name'] === 'video'): ?>
                                                    <div class="post-mini__image-wrapper">
                                                        <?= embed_youtube_cover(htmlspecialchars($post['video']), 109, 109); ?>
                                                        <!--<img class="post-mini__image" src="../img/coast-small.png" width="109" height="109" alt="Превью публикации">-->
                                                        <span class="post-mini__play-big">
                                                            <svg class="post-mini__play-big-icon" width="12" height="13">
                                                            <use xlink:href="#icon-video-play-big"></use>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <span class="visually-hidden">Видео</span>
                                                <?php elseif ($post['class_name'] === 'quote'): ?>
                                                    <span class="visually-hidden">Ссылка</span>
                                                    <svg class="post-mini__preview-icon" width="21" height="18">
                                                        <use xlink:href="#icon-filter-link"></use>
                                                    </svg>
                                                <?php elseif ($post['class_name'] === 'link'): ?>
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
                        </section>
                    <?php elseif (isset($_GET['tab']) && $_GET['tab'] === 'follows'): ?>
                        <section class="profile__subscriptions tabs__content tabs__content--active">
                            <h2 class="visually-hidden">Подписки</h2>
                            <ul class="profile__subscriptions-list">
                                <li class="post-mini post-mini--photo post user">
                                    <div class="post-mini__user-info user__info">
                                        <div class="post-mini__avatar user__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="post-mini__name-wrapper user__name-wrapper">
                                            <a class="post-mini__name user__name" href="#">
                                                <span>Петр Демин</span>
                                            </a>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5
                                                лет
                                                на сайте
                                            </time>
                                        </div>
                                    </div>
                                    <div class="post-mini__rating user__rating">
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                            <span class="post-mini__rating-amount user__rating-amount">556</span>
                                            <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                        </p>
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                            <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                        </p>
                                    </div>
                                    <div class="post-mini__user-buttons user__buttons">
                                        <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">
                                            Подписаться
                                        </button>
                                    </div>
                                </li>
                                <li class="post-mini post-mini--photo post user">
                                    <div class="post-mini__user-info user__info">
                                        <div class="post-mini__avatar user__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="post-mini__name-wrapper user__name-wrapper">
                                            <a class="post-mini__name user__name" href="#">
                                                <span>Петр Демин</span>
                                            </a>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5
                                                лет
                                                на сайте
                                            </time>
                                        </div>
                                    </div>
                                    <div class="post-mini__rating user__rating">
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                            <span class="post-mini__rating-amount user__rating-amount">556</span>
                                            <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                        </p>
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                            <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                        </p>
                                    </div>
                                    <div class="post-mini__user-buttons user__buttons">
                                        <button class="post-mini__user-button user__button user__button--subscription button button--quartz" type="button">
                                            Отписаться
                                        </button>
                                    </div>
                                </li>
                                <li class="post-mini post-mini--photo post user">
                                    <div class="post-mini__user-info user__info">
                                        <div class="post-mini__avatar user__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="post-mini__name-wrapper user__name-wrapper">
                                            <a class="post-mini__name user__name" href="#">
                                                <span>Петр Демин</span>
                                            </a>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5
                                                лет
                                                на сайте
                                            </time>
                                        </div>
                                    </div>
                                    <div class="post-mini__rating user__rating">
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                            <span class="post-mini__rating-amount user__rating-amount">556</span>
                                            <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                        </p>
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                            <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                        </p>
                                    </div>
                                    <div class="post-mini__user-buttons user__buttons">
                                        <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">
                                            Подписаться
                                        </button>
                                    </div>
                                </li>
                                <li class="post-mini post-mini--photo post user">
                                    <div class="post-mini__user-info user__info">
                                        <div class="post-mini__avatar user__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="post-mini__name-wrapper user__name-wrapper">
                                            <a class="post-mini__name user__name" href="#">
                                                <span>Петр Демин</span>
                                            </a>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5
                                                лет
                                                на сайте
                                            </time>
                                        </div>
                                    </div>
                                    <div class="post-mini__rating user__rating">
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                            <span class="post-mini__rating-amount user__rating-amount">556</span>
                                            <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                        </p>
                                        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                            <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                        </p>
                                    </div>
                                    <div class="post-mini__user-buttons user__buttons">
                                        <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">
                                            Подписаться
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </section>

                    <?php else: ?>
                        <section class="profile__posts tabs__content tabs__content--active">
                            <h2 class="visually-hidden">Публикации</h2>


                            <?php foreach ($userPosts as $post): ?>
                                <article class="profile__post post post-<?= $post['class_name'] ?>">
                                    <header class="post__header">
                                        <h2><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h2>
                                    </header>
                                    <div class="post__main">

                                        <?php if ($post['class_name'] === 'photo'): ?>
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

                                        <?php elseif ($post['class_name'] === 'text'): ?>
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

                                    <footer class="post__footer">
                                        <div class="post__indicators">
                                            <div class="post__buttons">
                                                <a class="post__indicator post__indicator--likes button <?= (in_array($post['id'], $liked_post_ids_by_session_user)) ? 'post__indicator--likes-active' : '' ?>"
                                                   href="profile.php?id=<?= $user['id'] ?>&liked_post_id=<?= $post['id'] ?>"
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
                                                <a class="post__indicator post__indicator--repost button <?= (in_array($post['id'], $reposted_post_ids_by_session_user)) ? 'post__indicator--repost-active' : '' ?>" href="profile.php?id=<?= $user['id'] ?>&reposted_post_id=<?= $post['id'] ?>" title="Репост">
                                                    <svg class="post__indicator-icon post__indicator-icon--repost-active" width="19" height="17">
                                                        <use xlink:href="#icon-repost"></use>
                                                    </svg>
                                                    <span><?= count_reposts_of_post($con, $post['id']) ?></span>
                                                    <span class="visually-hidden">количество репостов</span>
                                                </a>
                                            </div>
                                            <time class="post__time" datetime="<?= date_format(date_create($post['dt_add']), 'Y-m-d'); ?>"><?= print_date_diff($post['dt_add']); ?> назад</time>
                                        </div>
                                        <ul class="post__tags">
                                            <li><a href="#">#nature</a></li>
                                            <li><a href="#">#globe</a></li>
                                            <li><a href="#">#photooftheday</a></li>
                                            <li><a href="#">#canon</a></li>
                                            <li><a href="#">#landscape</a></li>
                                            <li><a href="#">#щикарныйвид</a></li>
                                        </ul>
                                    </footer>
                                    <div class="comments">
                                        <a class="comments__button button" href="#">Показать комментарии</a>
                                    </div>
                                </article>
                            <?php endforeach; ?>


                            <article class="profile__post post post-text">
                                <header class="post__header">
                                    <div class="post__author">
                                        <a class="post__author-link" href="#" title="Автор">
                                            <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                                                <img class="post__author-avatar" src="../img/userpic-tanya.jpg" alt="Аватар пользователя">
                                            </div>
                                            <div class="post__info">
                                                <b class="post__author-name">Репост: Таня Фирсова</b>
                                                <time class="post__time" datetime="2019-03-30T14:31">25 минут назад
                                                </time>
                                            </div>
                                        </a>
                                    </div>
                                </header>
                                <div class="post__main">
                                    <h2><a href="#">Полезный пост про Байкал</a></h2>
                                    <p>
                                        Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской
                                        границы. Байкал считается самым глубоким озером в мире. Он окружен сетью
                                        пешеходных
                                        маршрутов, называемых Большой байкальской тропой. Деревня Листвянка,
                                        расположенная
                                        на западном берегу озера, – популярная отправная точка для летних экскурсий.
                                        Зимой
                                        здесь можно кататься на коньках и собачьих упряжках.
                                    </p>
                                    <a class="post-text__more-link" href="#">Читать далее</a>
                                </div>
                                <footer class="post__footer">
                                    <div class="post__indicators">
                                        <div class="post__buttons">
                                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                                <svg class="post__indicator-icon" width="20" height="17">
                                                    <use xlink:href="#icon-heart"></use>
                                                </svg>
                                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                                    <use xlink:href="#icon-heart-active"></use>
                                                </svg>
                                                <span>250</span>
                                                <span class="visually-hidden">количество лайков</span>
                                            </a>
                                            <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                                                <svg class="post__indicator-icon" width="19" height="17">
                                                    <use xlink:href="#icon-repost"></use>
                                                </svg>
                                                <span>5</span>
                                                <span class="visually-hidden">количество репостов</span>
                                            </a>
                                        </div>
                                        <time class="post__time" datetime="2019-01-30T23:41">15 минут назад</time>
                                    </div>
                                    <ul class="post__tags">
                                        <li><a href="#">#nature</a></li>
                                        <li><a href="#">#globe</a></li>
                                        <li><a href="#">#photooftheday</a></li>
                                        <li><a href="#">#canon</a></li>
                                        <li><a href="#">#landscape</a></li>
                                        <li><a href="#">#щикарныйвид</a></li>
                                    </ul>
                                </footer>
                                <div class="comments">
                                    <div class="comments__list-wrapper">
                                        <ul class="comments__list">
                                            <li class="comments__item user">
                                                <div class="comments__avatar">
                                                    <a class="user__avatar-link" href="#">
                                                        <img class="comments__picture" src="../img/userpic-larisa.jpg" alt="Аватар пользователя">
                                                    </a>
                                                </div>
                                                <div class="comments__info">
                                                    <div class="comments__name-wrapper">
                                                        <a class="comments__user-name" href="#">
                                                            <span>Лариса Роговая</span>
                                                        </a>
                                                        <time class="comments__time" datetime="2019-03-20">1 ч назад
                                                        </time>
                                                    </div>
                                                    <p class="comments__text">
                                                        Красота!!!1!
                                                    </p>
                                                </div>
                                            </li>
                                            <li class="comments__item user">
                                                <div class="comments__avatar">
                                                    <a class="user__avatar-link" href="#">
                                                        <img class="comments__picture" src="../img/userpic-larisa.jpg" alt="Аватар пользователя">
                                                    </a>
                                                </div>
                                                <div class="comments__info">
                                                    <div class="comments__name-wrapper">
                                                        <a class="comments__user-name" href="#">
                                                            <span>Лариса Роговая</span>
                                                        </a>
                                                        <time class="comments__time" datetime="2019-03-18">2 дня назад
                                                        </time>
                                                    </div>
                                                    <p class="comments__text">
                                                        Озеро Байкал – огромное древнее озеро в горах Сибири к северу от
                                                        монгольской границы. Байкал считается самым глубоким озером в
                                                        мире.
                                                        Он окружен сетью пешеходных маршрутов, называемых Большой
                                                        байкальской тропой. Деревня Листвянка, расположенная на западном
                                                        берегу озера, – популярная отправная точка для летних экскурсий.
                                                        Зимой здесь можно кататься на коньках и собачьих упряжках.
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                        <a class="comments__more-link" href="#">
                                            <span>Показать все комментарии</span>
                                            <sup class="comments__amount">45</sup>
                                        </a>
                                    </div>
                                </div>
                                <form class="comments__form form" action="#" method="post">
                                    <div class="comments__my-avatar">
                                        <img class="comments__picture" src="../img/userpic-medium.jpg" alt="Аватар пользователя">
                                    </div>
                                    <textarea class="comments__textarea form__textarea" placeholder="Ваш комментарий"></textarea>
                                    <label class="visually-hidden">Ваш комментарий</label>
                                    <button class="comments__submit button button--green" type="submit">Отправить
                                    </button>
                                </form>
                            </article>
                        </section>
                    <? endif; ?>

                </div>
            </div>
        </div>
    </div>
</main>
