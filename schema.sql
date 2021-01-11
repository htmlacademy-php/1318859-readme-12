CREATE DATABASE readme
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    dt_add   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email    VARCHAR(128) NOT NULL UNIQUE,
    login    VARCHAR(64)  NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar   VARCHAR(255)
);

CREATE TABLE posts
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    dt_add       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    title        VARCHAR(255) NOT NULL,
    content      CHAR         NOT NULL,
    quote_author VARCHAR(255) NOT NULL,
    image        VARCHAR(255),
    video        VARCHAR(255) NOT NULL,
    link         VARCHAR(255) NOT NULL,
    views        INT,
    user_id      INT,
    type_title   VARCHAR(64)  NOT NULL,
    tag_id       INT
);

CREATE TABLE comments
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    dt_add  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    content CHAR NOT NULL,
    user_id INT,
    post_id INT
);

CREATE TABLE likes
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT
);

CREATE TABLE follows
(
    id                INT AUTO_INCREMENT PRIMARY KEY,
    follower_id       INT,
    following_user_id INT
);

CREATE TABLE messages
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    dt_add      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    content     CHAR NOT NULL,
    sender_id   INT,
    receiver_id INT
);

CREATE TABLE tags
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL
);

CREATE TABLE types
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(64) NOT NULL,
    icon_class_name VARCHAR(64) NOT NULL
);

CREATE INDEX p_title ON posts (title);
CREATE INDEX p_content ON posts (content);
CREATE INDEX t_name ON tags (name);
