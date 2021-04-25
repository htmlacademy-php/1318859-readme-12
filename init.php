<?php
session_start();

date_default_timezone_set('Europe/Moscow');

$con = mysqli_connect("localhost", "root", "","readme");
mysqli_set_charset($con, "utf8");
