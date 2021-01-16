-- создание типов постов;
INSERT INTO types
SET title
    =
    'post-quote',
    icon_class_name =
    'post-quote';
INSERT INTO types
SET title
    =
    'post-text',
    icon_class_name =
    'post-text';
INSERT INTO types
SET title
    =
    'post-photo',
    icon_class_name =
    'post-photo';
INSERT INTO types
SET title
    =
    'post-link',
    icon_class_name =
    'post-link';
INSERT INTO types
SET title
    =
    'post-video',
    icon_class_name =
    'post-video';

-- создание пользователей;
INSERT INTO users
SET email
    =
    'larisa@test.ru',
    login =
    'Лариса',
    password =
    'larisa777',
    avatar =
    'userpic-larisa-small.jpg';
INSERT INTO users
SET email
    =
    'vladik@test.ru',
    login =
    'Владик',
    password =
    'vladik777',
    avatar =
    'userpic.jpg';
INSERT INTO users
SET email
    =
    'viktor@test.ru',
    login =
    'Виктор',
    password =
    'viktor777',
    avatar =
    'userpic-mark.jpg';

-- создание комментариев;
INSERT INTO comments
SET content
    =
    'It is so interesting!',
    user_id =
    1,
    post_id =
    2;
INSERT INTO comments
SET content
    =
    'Wonderful!',
    user_id =
    2,
    post_id =
    4;

-- создание постов;
INSERT INTO posts
SET title
    =
    'Цитата',
    text_content =
    'Мы в жизни любим только раз, а после ищем лишь похожих',
    quote_author =
    'Лариса',
    views_count =
    5,
    user_id =
    1,
    type_id =
    1;
INSERT INTO posts
SET title
    =
    'Игра престолов',
    text_content =
    'Не могу дождаться начала финального сезона своего любимого сериала!',
    views_count =
    15,
    user_id =
    2,
    type_id =
    2;
INSERT INTO posts
SET title
    =
    'Наконец, обработал фотки!',
    image =
    'rock-medium.jpg',
    views_count =
    10,
    user_id =
    3,
    type_id =
    3;
INSERT INTO posts
SET title
    =
    'Моя мечта',
    image =
    'coast-medium.jpg',
    views_count =
    12,
    user_id =
    1,
    type_id =
    3;
INSERT INTO posts
SET title
    =
    'Лучшие курсы',
    link =
    'www.htmlacademy.ru',
    views_count =
    25,
    user_id =
    2,
    type_id =
    4;

-- получение списка постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT p.*, u.login, t.title FROM posts p JOIN users u ON p.user_id = u.id JOIN types t ON p.type_id = t.id  ORDER BY views_count DESC;

-- получение списка постов для пользователя №1;
SELECT * FROM posts WHERE user_id = 1;

-- получение списка комментариев для поста №2 с логином пользователя, оставившего комментарий;
SELECT c.*, u.login FROM comments c JOIN users u ON c.user_id = u.id WHERE post_id = 2;

-- добавление лайка посту №1 от пользователя №2;
INSERT INTO likes SET post_id = 1, user_id = 2;

-- подписка пользователя №3 на пользователя №1;
INSERT INTO follows SET follower_id = 3, following_user_id = 1;
