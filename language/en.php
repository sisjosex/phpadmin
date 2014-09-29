<?php

/* You can setup language keys for each text showed in admin */
$lang['submit_save'] = 'Save';

function lang($txt) {

    global $lang;

    if( isset($lang[$txt]) ) {
        return $lang[$txt];
    }

    return $txt;
}