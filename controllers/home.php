<?php

require_once(BASE_PATH . 'system/controller.php');

class Home extends Controller {


    function __construct() {

        parent::__construct();
    }


    function index() {

        $data['title'] = "Frontend Page";

        $this->view->add('frontend/home', $data);

        $this->view->render();
    }
}