<?php

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http";
$port = $_SERVER['SERVER_PORT'];

$port = ($port != '80') ? ':' . $port : '';

define('BASE_URL', $protocol . ':' . '//' . $_SERVER['SERVER_NAME'] . $port . '/test/framework2/');
$constants['BASE_URL'] = BASE_URL;

/* Default controllers by folder name*/
$default_controllers['admin'] = 'login';

define('FACEBOOK_URL','https://www.facebook.com/PremierFitnessClub');

/* Default controller name for default site (frontend) */
define('DEFAULT_CONTROLLER', 'home');

/* Default function inside ocntroller */
define('DEFAULT_FUNCTION', 'index');

/* Cookie time for session expiration */
define('AUTH_COOKIE_EXPIRE', 3600*60*24);

/* Cookie name for store session id */
define('AUTH_COOKIE_NAME', '_framework2');

/* Database profix for all tables */
define('DB_PREFIX', '');


/* Email from header Title */
define('MAIL_FROM_TITLE', 'Premier Group');

/* Email from address to show */
define('MAIL_FROM_ADDRESS', 'info@premier.com.bo');

/* Admin email for send copy emails */
define('MAIL_ADMIN_ADDRESS', 'sisjosex@gmail.com');
