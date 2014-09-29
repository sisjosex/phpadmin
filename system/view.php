<?php

class View {

    var $content = array();

    public static $instance = null;

    function __construct() {

    }

    /**
     * Singleton constructor
     * @return static
     */
    public static function getInstance () {
        if (is_null(self::$instance)) { self::$instance = new static(); }
        return self::$instance;
    }

    function add( $view, $params = null, $return_text_only = FALSE ) {

        if( is_array($params) ) {
            extract($params);
        }

        if( $view != '' ) {

            $path = BASE_PATH . '/views/' . $view . '.php';

            if( file_exists($path) ) {

                ob_start();
                include $path;

                $html = ob_get_clean();

                if(!$return_text_only) {

                    $this->content[] = $html;

                } else {

                    return $html;
                }

            } else {

                $this->error("View $view does not exists");
            }
        }
    }

    function putDefaults($modules) {

        foreach($modules as &$module) {

            if( !isset($module['settings']['template']) ) {

                $module['settings']['template'] = false;
            }
        }

        return $modules;
    }

    function render() {

        echo implode('', $this->content);
    }


    function error($msg) {

        echo $msg;
        die();
    }


    function json($object) {

        if(@$_REQUEST['debug']==1) {

            echo "GET<pre>";
            print_r($_GET);
            echo "</pre>POST<pre>";
            print_r($_POST);
            echo "</pre>REQUEST<pre>";
            print_r($_REQUEST);
            echo "</pre>FILES<pre>";
            print_r($_FILES);
            echo "</pre>RESPONSE<pre>";
            print_r($object);

        } else {

            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');

            echo json_encode($object);
        }
    }
}