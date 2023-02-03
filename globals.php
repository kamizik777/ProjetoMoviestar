<?php
ini_set("session.use_cookies", "1");
ini_set("session.use_only_cookies", "1");
ini_set("session.use_trans_sid", "0");

$name = md5('silvas' . $_SERVER['REMOTE_ADDR'] . '3' . $_SERVER['HTTP_USER_AGENT'] . 'caixa');

session_name($name);
session_set_cookie_params(0, "/", "", true, false);
session_start();


$BASE_URL = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"]. "?") . "/";