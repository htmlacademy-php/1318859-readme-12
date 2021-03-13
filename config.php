<?php
session_start();
define("NUMBER_OF_PAGE_POSTS", 6);

date_default_timezone_set('Europe/Moscow');

$con = mysqli_connect("localhost", "root", "","readme");
mysqli_set_charset($con, "utf8");
