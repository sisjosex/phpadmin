<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

define('BASE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once('./config/constants.php');
require_once('./config/application.php');

$port = $_SERVER['SERVER_PORT'];

if($port != '80') {
    $port = ':' . $port;
} else {
    $port = '';
}

$path = $protocol . ':' . '//' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];

$request = explode(BASE_URL, $path);

$request = explode('/', @$request[1]);

$controller = DEFAULT_CONTROLLER;
$function = DEFAULT_FUNCTION;
$params = array();

$firstTimeController = true;
$firstTimeFunction = true;
$path = BASE_PATH . 'controllers';

$controller_path = $path;
$controllerNotExists = false;


if( !empty($request) ) {

    foreach($request as $uri) {

        if( !empty($uri) ) {

            $path .= DIRECTORY_SEPARATOR . $uri;

            if(is_dir($path))
                continue;

            if($firstTimeController === true) {

                $path .= '.php';
                $controller_path = $path;

                if( file_exists($path) )
                    $controller = $uri;
                else
                    $controllerNotExists = true;

                $firstTimeController = false;

            } else if($firstTimeFunction === true) {

                $function = $uri;
                $firstTimeFunction = false;

            } else {

                $params[] = $uri;
            }
        }
    }
}

if($path != $controller_path) {

    //$controller = DEFAULT_FOLDER_CONTROLLER;
    //$controller_path = $path . DIRECTORY_SEPARATOR;
}


if($controllerNotExists) {

    error_404("Controller does not exists $controller_path");

} else {

    if($controller === DEFAULT_CONTROLLER) {
        $controller_path = BASE_PATH . 'controllers' . DIRECTORY_SEPARATOR . $controller . '.php';
    }

    if( !file_exists($controller_path) && !is_dir($controller_path) ) {
        error_404("Controller does not exists $controller_path");
    } else if(is_dir($path)) {

        $dir = explode(DIRECTORY_SEPARATOR, $path);
        $dir = end($dir);

        if(isset($default_controllers[$dir])) {

            $controller = $default_controllers[$dir];
            $controller_path = $path . DIRECTORY_SEPARATOR . $controller . '.php';
        }
    }

    require_once($controller_path);
    eval("\$controller_instance = new " . ucfirst($controller) . "();");


    if( !method_exists($controller_instance, $function) ) {

        error_404("Function does not exists $controller_path method: $function");

    } else {

        call_user_func_array(array($controller_instance, $function), $params);
    }
}


function error_404($msg) {

    echo $msg;
    die();
}
