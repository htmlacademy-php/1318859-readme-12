<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <?php foreach ($tabs as $post_type => $tab_name): ?>
                            <li class="adding-post__tabs-item filters__item">
                                <a class="adding-post__tabs-link filters__button filters__button--<?= $post_type; ?> <?php if ($current_tab === $post_type): ?>filters__button--active tabs__item--active<?php endif; ?> tabs__item button" href="add.php?type=<?= $post_type; ?>">
                                    <svg class="filters__icon" width="22" height="18">
                                        <use xlink:href="#icon-filter-<?= $post_type; ?>"></use>
                                    </svg>
                                    <span><?= $tab_name; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="adding-post__tab-content">
                    <section class="adding-post__<?= $current_tab; ?> tabs__content tabs__content--active">
                        <h2 class="visually-hidden">
                            <?= $forms[$current_tab]['title']; ?>
                        </h2>
                        <form class="adding-post__form form" action="add.php?type=<?= $current_tab; ?>" method="post" <?php if ($current_tab === 'photo'): ?>enctype="multipart/form-data"<?php endif; ?>>
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <input type="hidden" name="type" value="<?= $current_tab; ?>">

                                    <?php foreach ($forms[$current_tab]['inputs'] as $input): ?>

                                        <?php if ($input['field_type'] === 'input-file'): ?>
                                            <div class="adding-post__<?= $input['field_type']; ?>-container form__input-container form__input-container--file">
                                                <div class="adding-post__<?= $input['field_type']; ?>-wrapper form__<?= $input['field_type']; ?>-wrapper">
                                                    <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                                        <input class="adding-post__input-file form__input-file"
                                                               id="<?= $current_tab; ?>-<?= $input['name']; ?>"
                                                               type="<?= $input['type']; ?>"
                                                               name="<?= $current_tab; ?>-<?= $input['name']; ?>"
                                                               title="">
                                                        <div class="form__file-zone-text">
                                                            <span>Перетащите фото сюда</span>
                                                        </div>
                                                    </div>
                                                    <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                                                        <span>Выбрать фото</span>
                                                        <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                                                            <use xlink:href="#icon-attach"></use>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="adding-post__file adding-post__file--photo form__file dropzone-previews"></div>
                                            </div>
                                        <?php else: ?>

                                            <div class="adding-post__<?= $input['field_type']; ?>-wrapper form__<?= $input['field_type']; ?>-wrapper">
                                                <label class="adding-post__label form__label" for="<?= $current_tab; ?>-<?= $input['name']; ?>">
                                                    <?= $input['title']; ?> <?= ($input['required']) ? '<span class="form__input-required">*</span>' : ''; ?>
                                                </label>
                                                <div class="form__input-section <?php if (isset($input['error'])): ?>form__input-section--error<?php endif; ?>">

                                                    <?php if ($input['field_type'] === 'input'): ?>
                                                        <input class="adding-post__input form__input"
                                                               id="<?= $current_tab; ?>-<?= $input['name']; ?>"
                                                               type="<?= $input['type']; ?>"
                                                               name="<?= $current_tab; ?>-<?= $input['name']; ?>"
                                                               value="<?= $_POST[$current_tab . '-' . $input['name']] ?? ''; ?>"
                                                               placeholder="<?= $input['placeholder'] ?? ''; ?>">
                                                    <?php elseif ($input['field_type'] === 'textarea'): ?>
                                                        <textarea class="adding-post__textarea form__textarea form__input"
                                                                  id="<?= $current_tab; ?>-<?= $input['name']; ?>"
                                                                  name="<?= $current_tab; ?>-<?= $input['name']; ?>"
                                                                  placeholder="<?= $input['placeholder'] ?? ''; ?>"><?= $_POST[$current_tab . '-' . $input['name']] ?? ''; ?></textarea>
                                                    <?php endif; ?>

                                                    <button class="form__error-button button" type="button">
                                                        !<span class="visually-hidden">Информация об ошибке</span>
                                                    </button>
                                                    <div class="form__error-text">
                                                        <h3 class="form__error-title"><?= $input['title']; ?></h3>
                                                        <p class="form__error-desc"><?= $input['error']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <div class="adding-post__buttons">
                                        <button class="adding-post__submit button button--main" name="send" type="submit">
                                            Опубликовать
                                        </button>
                                        <a class="adding-post__close" href="#">Закрыть</a>
                                    </div>
                                </div>

                                <?php if (!empty($errors)): ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте
                                            следующие
                                            ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($forms[$current_tab]['inputs'] as $input): ?>
                                                <?php if (!empty($errors[$current_tab . '-' . $input['name']])): ?>
                                                    <li class="form__invalid-item">
                                                        <?= (isset($input['title'])) ? $input['title'] . '. ' : ''?><?= $errors[$current_tab . '-' . $input['name']]; ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>

                                            <? /*
                                                <li class="form__invalid-item">Теги. Нужно указать минимум один тег.
                                                    Каждый тег должен состоять из одного слова и отделяться пробелом
                                                </li>
 */ ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </section>
                    <? /*
                        <?php foreach ($types as $type): ?>
                            <?php if ($type_name === $type['class_name']): ?>
                                <section class="adding-post__<?= $type['class_name']; ?> tabs__content tabs__content--active">
                                    <h2 class="visually-hidden">Форма добавления
                                        <?php
                                        if ($type['class_name'] === 'photo') {
                                            echo " фото";
                                        } elseif ($type['class_name'] === 'video') {
                                            echo " видео";
                                        } elseif ($type['class_name'] === 'text') {
                                            echo " текста";
                                        } elseif ($type['class_name'] === 'quote') {
                                            echo " цитаты";
                                        } elseif ($type['class_name'] === 'link') {
                                            echo " ссылки";
                                        }
                                        ?>
                                    </h2>
                                    <form class="adding-post__form form" action="add.php" method="post" <?php if ($type['class_name'] === 'photo'): ?>enctype="multipart/form-data"<?php endif; ?>>
                                        <div class="form__text-inputs-wrapper">
                                            <div class="form__text-inputs">
                                                <input type="hidden" name="type" value="<?= $type['class_name']; ?>">
                                                <div class="adding-post__input-wrapper form__input-wrapper">
                                                    <label class="adding-post__label form__label" for="<?= $type['class_name']; ?>-heading">
                                                        Заголовок
                                                        <span class="form__input-required">*</span>
                                                    </label>
                                                    <div class="form__input-section <?php if (isset($errors[$type['class_name'] . '-heading'])): ?>form__input-section--error<?php endif; ?>">
                                                        <input class="adding-post__input form__input" id="<?= $type['class_name']; ?>-heading" type="text" name="<?= $type['class_name']; ?>-heading" value="<?= getPostVal($type['class_name'] . '-heading'); ?>" placeholder="Введите заголовок">
                                                        <button class="form__error-button button" type="button">
                                                            !<span class="visually-hidden">Информация об ошибке</span>
                                                        </button>
                                                        <div class="form__error-text">
                                                            <h3 class="form__error-title">Заголовок</h3>
                                                            <p class="form__error-desc"><?= $errors[$type['class_name'] . '-heading']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($type['class_name'] === 'photo'): ?>
                                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                                        <label class="adding-post__label form__label" for="photo-url">
                                                            Ссылка из интернета
                                                        </label>
                                                        <div class="form__input-section <?php if (isset($errors['photo-url'])): ?>form__input-section--error<?php endif; ?>">
                                                            <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-url" value="<?= getPostVal("photo-url"); ?>" placeholder="Введите ссылку">
                                                            <button class="form__error-button button" type="button">
                                                                !<span class="visually-hidden">Информация об ошибке</span>
                                                            </button>
                                                            <div class="form__error-text">
                                                                <h3 class="form__error-title">Ссылка из
                                                                    интернета</h3>
                                                                <p class="form__error-desc"><?= $errors['photo-url']; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php elseif ($type['class_name'] === 'video'): ?>
                                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                                        <label class="adding-post__label form__label" for="video-url">
                                                            Ссылка youtube
                                                            <span class="form__input-required">*</span>
                                                        </label>
                                                        <div class="form__input-section <?php if (isset($errors['video-url'])): ?>form__input-section--error<?php endif; ?>">
                                                            <input class="adding-post__input form__input" id="video-url" type="text" name="video-url" value="<?= getPostVal("video-url"); ?>" placeholder="Введите ссылку">
                                                            <button class="form__error-button button" type="button">
                                                                !<span class="visually-hidden">Информация об ошибке</span>
                                                            </button>
                                                            <div class="form__error-text">
                                                                <h3 class="form__error-title">Ссылка
                                                                    youtube</h3>
                                                                <p class="form__error-desc"><?= $errors['video-url']; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php elseif ($type['class_name'] === 'text'): ?>
                                                    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                                        <label class="adding-post__label form__label" for="text-post">
                                                            Текст поста
                                                            <span class="form__input-required">*</span>
                                                        </label>
                                                        <div class="form__input-section <?php if (isset($errors['text-post'])): ?>form__input-section--error<?php endif; ?>">
                                                    <textarea class="adding-post__textarea form__textarea form__input"
                                                              id="text-post"
                                                              name="text-post"
                                                              placeholder="Введите текст публикации"><?= getPostVal("text-post"); ?></textarea>
                                                            <button class="form__error-button button" type="button">
                                                                !<span class="visually-hidden">Информация об ошибке</span>
                                                            </button>
                                                            <div class="form__error-text">
                                                                <h3 class="form__error-title">Текст
                                                                    поста</h3>
                                                                <p class="form__error-desc"><?= $errors['text-post']; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php elseif ($type['class_name'] === 'quote'): ?>

                                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                                        <label class="adding-post__label form__label" for="quote-text">
                                                            Текст цитаты
                                                            <span class="form__input-required">*</span>
                                                        </label>
                                                        <div class="form__input-section <?php if (isset($errors['quote-text'])): ?>form__input-section--error<?php endif; ?>">
                                                    <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input"
                                                              id="quote-text"
                                                              name="quote-text"
                                                              placeholder="Текст цитаты"><?= getPostVal("quote-text"); ?></textarea>
                                                            <button class="form__error-button button" type="button">
                                                                !<span class="visually-hidden">Информация об ошибке</span>
                                                            </button>
                                                            <div class="form__error-text">
                                                                <h3 class="form__error-title">Текст
                                                                    цитаты</h3>
                                                                <p class="form__error-desc"><?= $errors['quote-text']; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="adding-post__input-wrapper form__textarea-wrapper">
                                                        <label class="adding-post__label form__label" for="quote-author">
                                                            Автор
                                                            <span class="form__input-required">*</span>
                                                        </label>
                                                        <div class="form__input-section <?php if (isset($errors['quote-author'])): ?>form__input-section--error<?php endif; ?>">
                                                            <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author" value="<?= getPostVal("quote-author"); ?>">
                                                            <button class="form__error-button button" type="button">
                                                                !<span class="visually-hidden">Информация об ошибке</span>
                                                            </button>
                                                            <div class="form__error-text">
                                                                <h3 class="form__error-title">Автор</h3>
                                                                <p class="form__error-desc"><?= $errors['quote-author']; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php elseif ($type['class_name'] === 'link'): ?>
                                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                                        <label class="adding-post__label form__label" for="link-url">
                                                            Ссылка
                                                            <span class="form__input-required">*</span>
                                                        </label>
                                                        <div class="form__input-section <?php if (isset($errors['link-url'])): ?>form__input-section--error<?php endif; ?>">
                                                            <input class="adding-post__input form__input" id="link-url" type="text" name="link-url" value="<?= getPostVal("link-url"); ?>">
                                                            <button class="form__error-button button" type="button">
                                                                !<span class="visually-hidden">Информация об ошибке</span>
                                                            </button>
                                                            <div class="form__error-text">
                                                                <h3 class="form__error-title">Ссылка</h3>
                                                                <p class="form__error-desc"><?= $errors['link-url']; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="adding-post__input-wrapper form__input-wrapper">
                                                    <label class="adding-post__label form__label" for="<?= $type['class_name']; ?>-tags">
                                                        Теги
                                                    </label>
                                                    <div class="form__input-section <?php if (isset($errors[$type['class_name'] . '-tags'])): ?>form__input-section--error<?php endif; ?>">
                                                        <input class="adding-post__input form__input" id="<?= $type['class_name']; ?>-tags" type="text" name="<?= $type['class_name']; ?>-tags" value="<?= getPostVal($type['class_name'] . '-tags'); ?>" placeholder="Введите теги">
                                                        <button class="form__error-button button" type="button">
                                                            !<span class="visually-hidden">Информация об ошибке</span>
                                                        </button>
                                                        <div class="form__error-text">
                                                            <h3 class="form__error-title">Теги</h3>
                                                            <p class="form__error-desc"><?= $errors[$type['class_name'] . '-tags']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($type['class_name'] === 'photo'): ?>
                                                    <div class="adding-post__input-file-container form__input-container form__input-container--file">
                                                        <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                                            <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                                                <input class="adding-post__input-file form__input-file" id="photo-userpic-file" type="file" name="photo-userpic-file" title="">
                                                                <div class="form__file-zone-text">
                                                                    <span>Перетащите фото сюда</span>
                                                                </div>
                                                            </div>
                                                            <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                                                                <span>Выбрать фото</span>
                                                                <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                                                                    <use xlink:href="#icon-attach"></use>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

                                                        </div>
                                                    </div>
                                                <?php endif; ?>


                                                <div class="adding-post__buttons">
                                                    <button class="adding-post__submit button button--main" name="send" type="submit">
                                                        Опубликовать
                                                    </button>
                                                    <a class="adding-post__close" href="#">Закрыть</a>
                                                </div>
                                            </div>


                                            <?php if (!empty($errors)): ?>
                                                <div class="form__invalid-block">
                                                    <b class="form__invalid-slogan">Пожалуйста, исправьте
                                                        следующие
                                                        ошибки:</b>
                                                    <ul class="form__invalid-list">
                                                        <?php if (isset($errors[$type['class_name'] . '-heading'])): ?>
                                                            <li class="form__invalid-item">
                                                                Заголовок. <?= $errors[$type['class_name'] . '-heading'] ?>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if ($type['class_name'] === 'photo'): ?>
                                                            <?php if (isset($errors['photo-url'])): ?>
                                                                <li class="form__invalid-item">
                                                                    <?= $errors['photo-url'] ?>
                                                                </li>
                                                            <?php endif; ?>

                                                            <!--<li class="form__invalid-item">Укажите ссылку на картинку или
                                                                загрузите
                                                                файл с компьютера.
                                                            </li>
                                                            <li class="form__invalid-item">Некорректный URL-адрес.</li>
                                                            <li class="form__invalid-item">Не удалось загрузить файл по
                                                                ссылке.
                                                            </li>-->

                                                            <?php if (isset($errors['photo-userpic-file'])): ?>
                                                                <li class="form__invalid-item">
                                                                    <?= $errors['photo-userpic-file'] ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php elseif ($type['class_name'] === 'video'): ?>
                                                            <?php if (isset($errors['video-url'])): ?>
                                                                <li class="form__invalid-item">
                                                                    Ссылка
                                                                    youtube. <?= $errors['video-url'] ?>
                                                                </li>
                                                            <?php endif; ?>

                                                        <?php elseif ($type['class_name'] === 'text'): ?>
                                                            <?php if (isset($errors['text-post'])): ?>
                                                                <li class="form__invalid-item">
                                                                    Текст поста. <?= $errors['text-post'] ?>
                                                                </li>
                                                            <?php endif; ?>

                                                        <?php elseif ($type['class_name'] === 'quote'): ?>
                                                            <?php if (isset($errors['quote-text'])): ?>
                                                                <li class="form__invalid-item">
                                                                    Цитата. <?= $errors['quote-text'] ?>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if (isset($errors['quote-author'])): ?>
                                                                <li class="form__invalid-item">
                                                                    Автор. <?= $errors['quote-author'] ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php elseif ($type['class_name'] === 'link'): ?>
                                                            <?php if (isset($errors['link-url'])): ?>
                                                                <li class="form__invalid-item">
                                                                    Ссылка. <?= $errors['link-url'] ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </form>
                                </section>
                            <? endif; ?>
                        <?php endforeach; ?>
 */ ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php if (isset($_GET['success'])): ?>
    <div class="modal modal--adding">
        <div class="modal__wrapper">
            <button class="modal__close-button button" type="button">
                <svg class="modal__close-icon" width="18" height="18">
                    <use xlink:href="#icon-close"></use>
                </svg>
                <span class="visually-hidden">Закрыть модальное окно</span></button>
            <div class="modal__content">
                <h1 class="modal__title">Пост добавлен</h1>
                <p class="modal__desc">
                    Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал
                    считается самым глубоким озером в мире. Он окружен сефтью пешеходных маршрутов, называемых Большой
                    байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная
                    отправная точка для летних экскурсий.
                </p>
                <div class="modal__buttons">
                    <a class="modal__button button button--main" href="#">Синяя кнопка</a>
                    <a class="modal__button button button--gray" href="#">Серая кнопка</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
