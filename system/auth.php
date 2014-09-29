<?php

require_once(BASE_PATH."/system/module.php");

class Auth extends Module {

    public static $user;

    function __construct() {

        parent::__construct("user", "id");

        $this->phisical_table = DB_PREFIX . "user";

        $this->addField(array(
            'key' => 'id',
            'name' => lang('ID'),
            'type' => 'hidden'
        ));

        $this->addField(array(
            'key' => 'email',
            'name' => lang('Email'),
            'type' => 'email',
            'label' => false,
            'validation' => array(
                'rules' => array(
                    'required' => true,
                    'email' => true
                ),
                'messages' => array(
                    'required' => lang('Email is required'),
                    'email' => lang('Invalid email address')
                )
            ),
            //'form_template' => '<div><input id="user_email"/></div>',
            'attr' => array(
                array('name' => 'autocomplete', 'value' => 'off'),
                array('name' => 'placeholder', 'value' => lang('Email'))
            )
        ));

        $this->addField(array(
            'key' => 'password',
            'name' => lang('Password'),
            'type' => 'password',
            'label' => false,
            'validation' => array(
                'rules' => array(
                    'required' => true
                ),
                'messages' => array(
                    'required' => lang('Password is required')
                )
            ),
            //'form_template' => '<div><input id="user_password" /></div>',
            'depends' => array(
                array('key' => 'email', 'type' => 'hide_if', 'value' => 'asd@asd.com')
            ),
            'attr' => array(
                array('name' => 'autocomplete', 'value' => 'off'),
                array('name' => 'placeholder', 'value' => lang('Password'))
            )
        ));

        $this->addGroup('form', array('email', 'password'));
    }


    function is_logged() {

        if( isset( $_COOKIE[AUTH_COOKIE_NAME] ) ) {

            $result = $this->get_where( array($this->table_id => $_COOKIE[AUTH_COOKIE_NAME] ) );

            if( count($result) == 1 && is_array($result) ) {

                Auth::$user = $result[0];

                return TRUE;
            }
        }

        return FALSE;
    }


    function logout( ) {

        setcookie( AUTH_COOKIE_NAME, FALSE, time(), '/' );
        unset( $_COOKIE[AUTH_COOKIE_NAME] );


        if(session_id() != '') {

            foreach($_SESSION as $key => $val) {
                unset( $_SESSION[$key] );
            }

            session_destroy();
        }
    }


    function authenticate( $user, $callback = "" ) {

        $user['password'] = md5( $user['password'] );

        $result = $this->get_where( $user );

        if( is_array($result) && count($result) == 1 ) {

            $user = $result[0];

            setcookie( AUTH_COOKIE_NAME, $user[$this->table_id], time() + AUTH_COOKIE_EXPIRE, '/' );

            Auth::$user = $user;

            if( ($callback && $callback($user)) || !$callback ) {

                return TRUE;
            }
        }

        return FALSE;
    }

}