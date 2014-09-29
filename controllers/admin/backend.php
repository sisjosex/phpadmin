<?php

require_once(BASE_PATH . 'system/auth.php');
require_once(BASE_PATH . 'system/controller.php');
require_once(BASE_PATH . 'system/view.php');
require_once(BASE_PATH . 'helpers/util.php');
require_once(BASE_PATH . 'language/en.php');

class Backend extends Controller {

    function __construct() {

	    parent::__construct();

        $auth = Auth::getInstance();

        if( $auth->is_logged() || isset($_REQUEST['API']) ) {

            if(session_id() == '') {
                session_start();
            }

        } else {

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                $this->view->json(array( 'logged' => false ));

                exit();

            } else {

                redirect(BASE_URL . 'admin/login');
            }
        }

        $this->view = new View();
    }


    function save() {

        $response = $this->model->save();

        $this->view->json($response);
    }


    /*
     * Send json object for add custom form data
     */
    function getNew() {

        $data = $this->model->getNew();

        $data['status'] = 'success';


        $this->view->json( $data );
    }

    function getEdit() {

        $data = $this->model->getEdit();

        if($data) {

            $data['status'] = 'success';

        } else {

            $data['status'] = 'fail';
            $data['message'] = lang('Record not found');
        }

        $this->view->json( $data );
    }

    function getList() {

        $data['total'] = $this->model->getList(TRUE);
        $data['list'] = $this->model->getList(FALSE);

        if($data) {

            $data['status'] = 'success';

        } else {

            $data['status'] = 'fail';
            $data['message'] = lang('Record not found');
        }

        $this->view->json( $data );
    }

    function delete() {

        $this->model->delete();

        $data['status'] = 'success';

        $this->view->json( $data );
    }

    function loadDepends() {

        $this->view->json( $this->model->loadDepends() );
    }

    function upload() {

        $result = $this->model->upload();

        if( is_array($result) ) {

            $this->view->json(
                $result
            );

        } else {

            echo $result;
        }
    }
}
