<?php

$lang['submit_save'] = 'Save';
$lang['submit_edit'] = 'Edit';
$lang['submit_cancel'] = 'Cancel';
$lang['Company'] = 'Empresa';
$lang['Company'] = 'Empresa';

function lang($txt) {

    if( isset($lang[$txt]) ) {
        return $lang[$txt];
    }

    return $txt;
}