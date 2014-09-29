<?php

require_once(BASE_PATH . 'system/controller.php');
require_once(BASE_PATH . 'system/auth.php');
require_once(BASE_PATH . 'system/module.php');

class Login extends Controller {


    function __construct() {

        parent::__construct();

        $this->model = new Auth('user', 'id');

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            if( $this->model->is_logged() ) {

                $this->view->json(
                    array(
                        'status' => 'success',
                        'reload' => BASE_URL . 'admin'
                    )
                );

                exit();
            }

        } else {

            if( $this->model->is_logged() ) {

                header( 'Location: ' . BASE_URL . 'admin/dashboard' );
            }
        }
    }


    function index() {

        global $lang;
        require_once(BASE_PATH . 'system/view.php');
        require_once(BASE_PATH . 'language/en.php');

        $data['title'] = lang("Admin Login");

        $data['modules'][] = array(
            'model' => $this->model->purgeModel(),
            'settings' => array(
                'container' => '#page',
                'class' => 'page'
            ),
            'containers' => array(
                'form' => array(
                    'settings' => array(
                        'class' => 'login-form',
                        'buttons' => array(
                            'submit' => array(
                                'value' => lang('Sign In'),
                                'url' => 'admin/login/authenticate',
                                'key' => 'submit'),
                            )
                        ),
                        'validation' => 'login'
                    )
                )
            );

        $this->view = new View();

        $data['modules'] = $this->view->putDefaults($data['modules']);
        $data['language'] = $lang;

        $this->view->add('admin/layouts/login', $data);

        $this->view->render();
    }


    function authenticate() {

        $authentication_params = array(
            'email' => $_REQUEST['user']['email'],
            'password' => $_REQUEST['user']['password'],
            'status' => 'active',
        );

        $result = $this->model->authenticate($authentication_params);

        if( $result === TRUE ) {

            $response = array(
                'status' => 'success',
                'reload' => BASE_URL . 'admin/dashboard'
            );

        } else {

            $response = array(
                'status' => 'fail',
                'message' => lang('Invalid user or password')
            );
        }

        $this->view->json($response);
    }

    function logout() {

        $this->model->logout();

        header('Location: ' . BASE_URL . 'admin/login');
    }
}