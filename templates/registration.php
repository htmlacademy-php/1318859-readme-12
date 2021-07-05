<main class="page__main page__main--registration">
    <div class="container">
        <h1 class="page__title page__title--registration">Регистрация</h1>
    </div>
    <section class="registration container">
        <h2 class="visually-hidden">Форма регистрации</h2>
        <form class="registration__form form" action="reg.php" method="post" enctype="multipart/form-data">
            <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                    <?php foreach ($form['inputs'] as $input): ?>
                        <?php if ($input['field_type'] === 'input'): ?>
                            <div class="registration__input-wrapper form__input-wrapper">
                                <label class="registration__label form__label" for="<?= 'registration-' . $input['name']
                                ?? '' ?>"><?= $input['title'] ?? '' ?>
                                    <?php if ($input['required']): ?>
                                        <span class="form__input-required">*</span>
                                    <?php endif; ?>
                                </label>
                                <div class="form__input-section <?php if (!empty($errors[$form['name']])
                                    && isset($errors[$form['name']][$input['name']])
                                ): ?>form__input-section--error<?php endif; ?>">
                                    <input class="registration__input form__input" id="<?= 'registration-'
                                    . $input['name'] ?? '' ?>" type="<?= $input['type'] ??
                                    '' ?>" name="<?= $input['name'] ?? '' ?>" value="<?= $_POST[$input['name']] ??
                                    ''; ?>" placeholder="<?= $input['placeholder'] ?? '' ?>">
                                    <?php if (!empty($errors[$form['name']])
                                        && isset($errors[$form['name']][$input['name']])
                                    ): ?>
                                        <button class="form__error-button button" type="button">
                                            !<span class="visually-hidden">Информация об ошибке</span>
                                        </button>
                                        <div class="form__error-text">
                                            <h3 class="form__error-title"><?= $input['title'] ?? '' ?></h3>
                                            <p class="form__error-desc">
                                                <?= $errors[$form['name']][$input['name']]; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($errors[$form['name']]) && isset($errors[$form['name']][$input['name']])): ?>
                    <div class="form__invalid-block">
                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                        <ul class="form__invalid-list">
                            <?php foreach ($form['inputs'] as $input): ?>
                                <li class="form__invalid-item">
                                    <?= (isset($input['title'])) ? $input['title'] . '. '
                                        : '' ?><?= $errors[$form['name']][$input['name']]; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="registration__input-file-container form__input-container form__input-container--file">
                <?php foreach ($form['inputs'] as $input): ?>
                    <?php if ($input['field_type'] === 'input-file'): ?>
                        <div class="registration__input-file-wrapper form__input-file-wrapper">
                            <div class="registration__file-zone form__file-zone dropzone">
                                <input class="registration__input-file form__input-file" id="<?= $input['name'] ??
                                '' ?>" type="<?= $input['type'] ?? '' ?>" name="<?= $input['name'] ?? '' ?>" title=" ">
                                <div class="form__file-zone-text">
                                    <span>Перетащите фото сюда</span>
                                </div>
                            </div>
                            <button class="registration__input-file-button form__input-file-button button"
                                    type="button">
                                <span>Выбрать фото</span>
                                <svg class="registration__attach-icon form__attach-icon" width="10" height="20">
                                    <use xlink:href="#icon-attach"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="registration__file form__file dropzone-previews"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <button class="registration__submit button button--main" type="submit">Отправить</button>
        </form>
    </section>
</main>
