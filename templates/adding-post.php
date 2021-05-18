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
                                <a class="adding-post__tabs-link filters__button filters__button--<?= $post_type; ?> <?php if ($currentTab === $post_type): ?>filters__button--active tabs__item--active<?php endif; ?> tabs__item button" href="add.php?type=<?= $post_type; ?>">
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
                    <section class="adding-post__<?= $currentTab; ?> tabs__content tabs__content--active">
                        <h2 class="visually-hidden">
                            <?= $form['title']; ?>
                        </h2>
                        <form class="adding-post__form form" action="add.php?type=<?= $currentTab; ?>" method="post" <?php if ($currentTab === 'photo'): ?>enctype="multipart/form-data"<?php endif; ?>>
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <input type="hidden" name="type" value="<?= $currentTab; ?>">

                                    <?php foreach ($form['inputs'] as $input): ?>

                                        <?php if ($input['field_type'] === 'input-file'): ?>
                                            <div class="adding-post__<?= $input['field_type']; ?>-container form__input-container form__input-container--file">
                                                <div class="adding-post__<?= $input['field_type']; ?>-wrapper form__<?= $input['field_type']; ?>-wrapper">
                                                    <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                                        <input class="adding-post__input-file form__input-file"
                                                               id="<?= $currentTab; ?>-<?= $input['name']; ?>"
                                                               type="<?= $input['type']; ?>"
                                                               name="<?= $currentTab; ?>-<?= $input['name']; ?>"
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
                                                <label class="adding-post__label form__label" for="<?= $currentTab; ?>-<?= $input['name']; ?>">
                                                    <?= $input['title']; ?> <?= ($input['required']) ? '<span class="form__input-required">*</span>' : ''; ?>
                                                </label>
                                                <div class="form__input-section <?php if (isset($errors[$form['name']][$input['name']])): ?>form__input-section--error<?php endif; ?>">

                                                    <?php if ($input['field_type'] === 'input'): ?>
                                                        <input class="adding-post__input form__input"
                                                               id="<?= $currentTab; ?>-<?= $input['name']; ?>"
                                                               type="<?= $input['type']; ?>"
                                                               name="<?= $currentTab; ?>-<?= $input['name']; ?>"
                                                               value="<?= $_POST[$currentTab . '-' . $input['name']] ?? ''; ?>"
                                                               placeholder="<?= $input['placeholder'] ?? ''; ?>">
                                                    <?php elseif ($input['field_type'] === 'textarea'): ?>
                                                        <textarea class="adding-post__textarea form__textarea form__input"
                                                                  id="<?= $currentTab; ?>-<?= $input['name']; ?>"
                                                                  name="<?= $currentTab; ?>-<?= $input['name']; ?>"
                                                                  placeholder="<?= $input['placeholder'] ?? ''; ?>"><?= $_POST[$currentTab . '-' . $input['name']] ?? ''; ?></textarea>
                                                    <?php endif; ?>

                                                    <button class="form__error-button button" type="button">
                                                        !<span class="visually-hidden">Информация об ошибке</span>
                                                    </button>
                                                    <div class="form__error-text">
                                                        <h3 class="form__error-title"><?= $input['title']; ?></h3>
                                                        <p class="form__error-desc"><?= $errors[$form['name']][$input['name']]; ?></p>
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

                                <?php if (!empty($errors[$form['name']])): ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте
                                            следующие
                                            ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($form['inputs'] as $input): ?>
                                                <?php if (!empty($errors[$form['name']][$input['name']])): ?>
                                                    <li class="form__invalid-item">
                                                        <?= (isset($input['title'])) ? $input['title'] . '. ' : '' ?><?= $errors[$form['name']][$input['name']]; ?>
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
