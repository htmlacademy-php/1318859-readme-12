INSERT INTO types SET
    title = 'Картинка',
    class_name = 'photo';
INSERT INTO types SET
    title = 'Видео',
    class_name = 'video';
INSERT INTO types SET
    title = 'Текст',
    class_name = 'text';
INSERT INTO types SET
    title = 'Цитата',
    class_name = 'quote';
INSERT INTO types SET
    title = 'Ссылка',
    class_name = 'link';

INSERT INTO users SET
    email = 'larisa@test.ru',
    login = 'Лариса',
    password = 'larisa777',
    avatar = '/img/userpic-larisa-small.jpg';
INSERT INTO users SET
    email = 'vladik@test.ru',
    login = 'Владик',
    password = 'vladik777',
    avatar = '/img/userpic.jpg';
INSERT INTO users SET
    email = 'viktor@test.ru',
    login = 'Виктор',
    password = 'viktor777',
    avatar = '/img/userpic-mark.jpg';

INSERT INTO comments SET
    content = 'It is so interesting!',
    user_id = 1,
    post_id = 2;
INSERT INTO comments SET
    content = 'Wonderful!',
    user_id = 2,
    post_id = 4;

INSERT INTO posts SET
    title = 'Цитата',
    text_content = 'Мы в жизни любим только раз, а после ищем лишь похожих',
    quote_author = 'Джейсон Стэтхем',
    views_count = 5,
    user_id = 1,
    type_id = 4;
INSERT INTO posts SET
    title = 'Игра престолов',
    text_content = 'Не могу дождаться начала финального сезона своего любимого сериала!',
    views_count = 15,
    user_id = 2,
    type_id = 3;
INSERT INTO posts SET
    title = 'Наконец, обработал фотки!',
    image = 'rock-medium.jpg',
    views_count = 10,
    user_id = 3,
    type_id = 1;
INSERT INTO posts SET
    title = 'Моя мечта',
    image = 'coast-medium.jpg',
    views_count = 12,
    user_id = 1,
    type_id = 1;
INSERT INTO posts SET
    title = 'Лучшие курсы',
    link = 'www.htmlacademy.ru',
    views_count = 25,
    user_id = 2,
    type_id = 5;

SELECT p.*, u.login, t.class_name FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id  ORDER BY views_count DESC;

SELECT * FROM posts WHERE user_id = 1;

SELECT c.*, u.login FROM comments c JOIN users u ON c.user_id = u.id WHERE post_id = 2;

INSERT INTO likes SET post_id = 1, user_id = 2;

INSERT INTO follows SET follower_id = 3, following_user_id = 1;
