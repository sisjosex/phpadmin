<?php

require_once(BASE_PATH . '/system/view.php');

class Controller {

    var $model;
    var $view;
    var $settings = array(
        'urls' => array(
            'add' => 'add',
            'edit' => 'edit',
            'save' => 'save'
        )
    );

    function __construct() {

        $this->view = new View();
    }
}
