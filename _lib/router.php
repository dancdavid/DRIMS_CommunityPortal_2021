<?php

/*
 * Written by Dan David
 */

function routerMain() {
    global $url;

    if (isset($url)) {
        $url_array = explode('/', $url);
        $view = strtolower($url_array[0]);

        
        if (file_exists(ROOT . '/html/' . $view . '.htm.php')) {
            include (ROOT . '/html/' . $view . '.htm.php');
        } else {
            include (ROOT . '/html/index.htm.php');
        }
 
    } else {
        include (ROOT . '/html/index.htm.php');
    }
    
}

function routerUsers() {
    global $url;
    
    if (isset($url)) {
        $url_array = explode('/', $url);
        $users_view = strtolower($url_array[0]);
        
        if (file_exists(ROOT . '/directory/html/' . $users_view . '.users.htm.php')) {
            include (ROOT . '/directory/html/' . $users_view . '.users.htm.php');
        } else {
            include (ROOT . '/directory/html/index.users.htm.php');
        }
    } else {
        include (ROOT . '/directory/html/index.users.htm.php');
    }
}
