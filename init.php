<?php
session_start();

date_default_timezone_set('Europe/Moscow');

$configs =[
    'con' => mysqli_connect("localhost", "root", "","readme"),
    'current_tab' => (isset($_GET["type"])) ? $_GET["type"] : 'text',
];

$con = mysqli_connect("localhost", "root", "","readme");
mysqli_set_charset($con, "utf8");
