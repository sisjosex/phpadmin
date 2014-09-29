<?php

require_once(BASE_PATH . 'controllers/admin/backend.php');

//require_once(BASE_PATH . 'models/dashboard.php');
require_once(BASE_PATH . 'models/user_model.php');

class Dashboard extends Backend
{

    function __construct()
    {

        parent::__construct();

        //$this->model = new DashboardModel();
    }

    function index()
    {

        global $lang;

        $userModule         = new UserModel();

        $data['title'] = lang("Admin Panel");

        if (Auth::$user['role'] == 'sadmin')
            $data['modules']['user'] = array(
                'model' => $userModule->purgeModel(),
                'settings' => array(
                    'container' => '#page',
                    'class' => 'page'
                ),
                'containers' => array(
                    'modal' => array(
                        'settings' => array()
                    ),
                    'form' => array(
                        'settings' => array()
                    ),
                    'grid' => array(
                        'settings' => array(
                            'actions' => array(
                                array('type' => 'edit', 'text' => lang('Edit'), 'icon' => ''),
                                array('type' => 'delete', 'text' => lang('Delete'), 'icon' => '')
                            )
                        )
                    ),
                    'toolbar' => array(
                        'settings' => array(
                            'actions' => array(
                                array(
                                    'type' => 'text',
                                    'title' => lang('Users Manager'),
                                    'template' => 'h2'
                                ),
                                array(
                                    'type' => 'text',
                                    'title' => lang('Manage users and manage roles'),
                                    'template' => 'h5'
                                ),
                                array(
                                    'type' => 'break'
                                ),
                                array(
                                    'type' => 'button',
                                    'title' => lang('New User'),
                                    'action' => 'new'
                                )
                            )
                        )
                    )
                )
            );



        $this->view = new View();

        $data['modules'] = $this->view->putDefaults($data['modules']);
        $data['sidebar'] = array();


        $data['sidebar'][] = array(
            'title' => lang(' MENU'),
            'type' => 'menu'
        );

        if (isset($data['modules']['user']))
            $data['sidebar'][] = array(
                'title' => lang('Manage Users'),
                'attr' => array(
                    'onclick' => 'javascript: ;'
                ),
                'icon' => '',
                'module' => 'user'
            );

        $data['language'] = $lang;

        $this->view->add('admin/layouts/backend', $data);

        $this->view->render();
    }
}