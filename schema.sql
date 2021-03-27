DROP DATABASE IF EXISTS readme;

CREATE DATABASE readme DEFAULT CHARACTER SET utf8
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
  text_content TEXT,
  quote_author VARCHAR(255),
  image        VARCHAR(255),
  video        VARCHAR(255),
  link         VARCHAR(255),
  views_count  INT,
  user_id      INT          NOT NULL,
  type_id      INT          NOT NULL,
  tag_id       INT
);

CREATE TABLE comments
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  dt_add  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  content TEXT NOT NULL,
  user_id INT,
  post_id INT
);

CREATE TABLE likes
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  post_id INT NOT NULL
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
  content     TEXT NOT NULL,
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
  title           VARCHAR(32) NOT NULL,
  class_name VARCHAR(32) NOT NULL
);

-- CREATE FULLTEXT INDEX p_title ON posts (title);
-- CREATE FULLTEXT INDEX p_text_content ON posts (text_content (40));
CREATE FULLTEXT INDEX ON posts (title, text_content (40));
CREATE FULLTEXT INDEX t_name ON tags (name);
